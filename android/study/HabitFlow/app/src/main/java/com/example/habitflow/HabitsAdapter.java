package com.example.habitflow;

import android.content.Context;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

public class HabitsAdapter extends RecyclerView.Adapter<HabitsAdapter.ViewHolder> {

    private final List<Habit> habits;
    private final Context context; // Храним контекст
    private final DatabaseHelper dbHelper; // Переменная для работы с базой данных

    public HabitsAdapter(Context context, List<Habit> habits) {
        this.context = context; // Передаем контекст
        this.habits = habits;
        this.dbHelper = new DatabaseHelper(context); // Инициализируем DatabaseHelper
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.habit_item, parent, false);

        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Habit habit = habits.get(position);

        SimpleDateFormat sdf = new SimpleDateFormat("dd.MM.yyyy");
        String currentDate = sdf.format(new Date());

        holder.habitName.setText(habit.getName());
        holder.habitCreated.setText(habit.getCreated());
        holder.habitStreak.setText(String.valueOf(habit.getStreak()));
        holder.habitGoal.setText(String.valueOf(habit.getGoal()));

        String iconName = habit.getIcon();
        int iconResId = holder.itemView.getContext().getResources()
                .getIdentifier(iconName, "drawable", holder.itemView.getContext().getPackageName());

        if (iconResId != 0) {
            holder.habitIcon.setImageResource(iconResId); // Устанавливаем картинку
        } else {
            // Если иконка не найдена, показываем дефолтную иконку
            holder.habitIcon.setImageResource(R.drawable.default_icon);
        }

        // Проверяем, был ли уже выполнен чекбокс сегодня
        if ((int) habit.getStreak() >= (int) habit.getGoal())
        {
            holder.habitIcon.setAlpha(0.5f);
            holder.habitName.setAlpha(0.5f);
            holder.habitCreated.setAlpha(0.5f);
            holder.habitStreak.setAlpha(0.5f);
            holder.habitGoal.setAlpha(0.5f);

            holder.habitCheckBox.setAlpha(0.5f);
            holder.habitCheckBox.setChecked(true);
            holder.habitCheckBox.setEnabled(false);
        } else {
            boolean isCheckedToday = currentDate.equals(habit.getLastCompletedDate());
            holder.habitCheckBox.setEnabled(!isCheckedToday);
            holder.habitCheckBox.setChecked(isCheckedToday);
        }

        // Устанавливаем слушатель на чекбокс
        holder.habitCheckBox.setOnCheckedChangeListener((buttonView, isChecked) -> {
            if (isChecked) {
                // Если чекбокс установлен, увеличиваем streak
                habit.incrementStreak();
                // Обновляем дату последнего выполнения
                habit.setLastCompletedDate(currentDate);
                // Сохраняем данные в базу данных
                updateHabitInDatabase(habit);

                // Добавляем достижение если цель выполнена
                addAchievementToDatabase(habit);

                // Блокируем чекбокс для текущего дня
                holder.habitCheckBox.setEnabled(false);

                holder.habitStreak.setText(String.valueOf(habit.getStreak()));
            } else {
                holder.habitCheckBox.setEnabled(true); // Разрешаем повторное выполнение
            }
        });
    }

    @Override
    public int getItemCount() {
        return habits.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        private final TextView habitName;
        private final TextView habitStreak;
        private final TextView habitGoal;
        private final TextView habitCreated;
        private final ImageView habitIcon;
        private final CheckBox habitCheckBox;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            habitName = itemView.findViewById(R.id.habitName);
            habitStreak = itemView.findViewById(R.id.habitStreak);
            habitGoal = itemView.findViewById(R.id.habitGoal);
            habitCreated = itemView.findViewById(R.id.habitCreated);
            habitIcon = itemView.findViewById(R.id.habitIcon);
            habitCheckBox = itemView.findViewById(R.id.habitCheckBox);
        }
    }

    private void updateHabitInDatabase(Habit habit) {
        SimpleDateFormat sdf = new SimpleDateFormat("dd.MM.yyyy");
        String currentDate = sdf.format(new Date());
        SQLiteDatabase db = dbHelper.getWritableDatabase();
        String updateQuery = "UPDATE habits SET last_completed_date = ?, streak = ? WHERE id_habit = ?";
        db.execSQL(updateQuery, new Object[]{currentDate, habit.getStreak(), habit.getId()});
        db.close();
    }

    private void addAchievementToDatabase(Habit habit) {
        SimpleDateFormat sdf = new SimpleDateFormat("dd.MM.yyyy");
        String currentDate = sdf.format(new Date());
        SQLiteDatabase db = dbHelper.getWritableDatabase();
        int streak;
        int goal;
        SharedPreferences preferences = context.getSharedPreferences("UserPrefs", Context.MODE_PRIVATE);
        int userId = preferences.getInt("id_user", 0);

        String selectQuery = "SELECT streak, goal FROM habits WHERE id_habit = ?";
        Cursor cursor = null;

        try {
            // Выполняем запрос
            cursor = db.rawQuery(selectQuery, new String[]{String.valueOf(habit.getId())});

            if (cursor.moveToFirst()) {
                // Читаем данные из запроса
                streak = cursor.getInt(cursor.getColumnIndexOrThrow("streak"));
                goal = cursor.getInt(cursor.getColumnIndexOrThrow("goal"));

                // Проверяем выполнение цели
                if (streak >= goal) {
                    // Цель достигнута
                    // TODO: Логика для достижения - сохранение нового достижения, уведомление пользователя и т.д.
                    String insertQuery = "INSERT INTO achievements (name, description, icon, date, id_user, id_habit) VALUES (?, ?, ?, ?, ?, ?)";
                    db.execSQL(insertQuery, new Object[]{"Достижение по привычке " + "'" + habit.getName() + "'", "Вы достигли цели - выполнили полезную привычку " + habit.getStreak() + " раз", habit.getIcon() + "_ach", currentDate, userId, habit.getId()});
                    //Toast.makeText(HabitsAdapter.this, "Привычка добавлена!", Toast.LENGTH_SHORT).show();
                }
            } else {
                // TODO: Логика, если запись не найдена
                Log.e("Database", "Habit not found for id_habit: " + habit.getId());
            }

        } catch (Exception e) {
            // Обрабатываем потенциальные ошибки
            e.printStackTrace();

        } finally {
            // Закрываем курсор в блоке finally
            if (cursor != null && !cursor.isClosed()) {
                cursor.close();
            }
        }
    }
}