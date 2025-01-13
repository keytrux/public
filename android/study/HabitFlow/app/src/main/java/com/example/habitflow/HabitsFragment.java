package com.example.habitflow;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import java.util.ArrayList;
import java.util.List;

public class HabitsFragment extends Fragment {

    DatabaseHelper dbHelper;
    Button btn_add;
    RecyclerView recyclerView;
    HabitsAdapter adapter;
    List<Habit> habits;

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_habits, container, false);

        dbHelper = new DatabaseHelper(getActivity());
        // Инициализация кнопки добавления привычки
        btn_add = view.findViewById(R.id.button_add);

        btn_add.setOnClickListener(view1 -> {
            Intent intent = new Intent(requireActivity(), HabitAdd.class);
            startActivity(intent);
        });

        // Инициализация RecyclerView
        recyclerView = view.findViewById(R.id.recyclerView_habits);
        recyclerView.setLayoutManager(new LinearLayoutManager(requireContext()));

        // Загрузка списка привычек
        habits = loadHabits();

        // Инициализация адаптера HabitAdapter
        adapter = new HabitsAdapter(getActivity(), habits);
        recyclerView.setAdapter(adapter);

        return view;
    }

    @Override
    public void onResume() {
        super.onResume();
        // Перезагрузка привычек после добавления
        habits.clear();
        habits.addAll(loadHabits());
        adapter.notifyDataSetChanged();
    }


    private List<Habit> loadHabits() {
        List<Habit> habitList = new ArrayList<>();

        SQLiteDatabase db = null;
        Cursor cursor = null;

        try {
            // Открытие базы данных
            db = dbHelper.getReadableDatabase();

            // Получаем ID текущего пользователя
            SharedPreferences preferences = requireActivity().getSharedPreferences("UserPrefs", Context.MODE_PRIVATE);
            int userId = preferences.getInt("id_user", 0);

            if (userId == -1) {
                Log.d("HabitsFragment", "Не найден id_user, список привычек пуст.");
                return habitList; // Возвращаем пустой список
            }

            // Выполнение запроса: выбрать привычки, принадлежащие текущему пользователю
            String query = "SELECT id_habit, name, icon, streak, goal, created, last_completed_date FROM habits WHERE id_user = ?";
            cursor = db.rawQuery(query, new String[]{String.valueOf(userId)});

            // Обрабатываем результаты запроса
            if (cursor.moveToFirst()) {
                do {
                    int id_habit = cursor.getInt(cursor.getColumnIndexOrThrow("id_habit"));
                    String name = cursor.getString(cursor.getColumnIndexOrThrow("name"));
                    String icon = cursor.getString(cursor.getColumnIndexOrThrow("icon"));
                    int streak = cursor.getInt(cursor.getColumnIndexOrThrow("streak"));
                    int goal = cursor.getInt(cursor.getColumnIndexOrThrow("goal"));
                    String created = cursor.getString(cursor.getColumnIndexOrThrow("created"));
                    String last_completed_date = cursor.getString(cursor.getColumnIndexOrThrow("last_completed_date"));

                    // Создаем объект Habit и добавляем в список
                    habitList.add(new Habit(id_habit, name, icon, streak, goal, created, last_completed_date));
                } while (cursor.moveToNext());
            }

        } catch (Exception e) {
            e.printStackTrace();
            Log.e("HabitsFragment", "Ошибка при загрузке привычек: " + e.getMessage());
        } finally {
            // Закрываем курсор и базу данных, чтобы избежать утечек
            if (cursor != null) cursor.close();
            if (db != null) db.close();
        }

        // Возвращаем список загруженных привычек
        return habitList;
    }
}