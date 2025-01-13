package com.example.habitflow;

import android.content.Context;
import android.content.SharedPreferences;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;

public class HabitAdd extends AppCompatActivity {

    DatabaseHelper dbHelper;
    EditText nameEt;
    Spinner goalSpinner;
    Button btn_icon, btn_save;
    ImageView selectedIconView; // Отображение выбранной иконки
    int selectedIconResId = R.drawable.default_icon; // Иконка по умолчанию

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_habit_add);

        dbHelper = new DatabaseHelper(this);
        SQLiteDatabase db = dbHelper.getWritableDatabase();

        nameEt = findViewById(R.id.habit_name);
        btn_icon = findViewById(R.id.button_icon);
        goalSpinner = findViewById(R.id.spinner_goal);
        btn_save = findViewById(R.id.button_save);
        selectedIconView = findViewById(R.id.selected_icon_view); // ImageView для отображения выбранной иконки

        String currentDate = new SimpleDateFormat("dd.MM.yyyy").format(new Date());

        // Инициализация спиннера целей
        ArrayAdapter<String> goalAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item,
                Arrays.asList("Выберите цель (количество выполнений)", "1", "3", "7", "10", "14", "30"));
        goalAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        goalSpinner.setAdapter(goalAdapter);

        // Слушатель на кнопку "Выбрать иконку"
        btn_icon.setOnClickListener(view -> openIconSelectionDialog());

        // Слушатель для кнопки "Сохранить"
        btn_save.setOnClickListener(view -> {
            if (nameEt.getText().toString().isEmpty())
            {
                Toast.makeText(this, "Вы не ввели название!", Toast.LENGTH_SHORT).show();
                return;
            }
            if (goalSpinner.getSelectedItemPosition() == 0) {
                Toast.makeText(this, "Вы не выбрали цель!", Toast.LENGTH_SHORT).show();
                return;
            }
            String selectedIconName = getResources().getResourceEntryName(selectedIconResId);
            SharedPreferences preferences = this.getSharedPreferences("UserPrefs", Context.MODE_PRIVATE);
            int userId = preferences.getInt("id_user", 0);
            String insertQuery = "INSERT INTO habits (name, icon, created, goal, streak, id_user) VALUES (?, ?, ?, ?, ?, ?)";
            db.execSQL(insertQuery, new Object[]{nameEt.getText().toString(), selectedIconName, currentDate, goalSpinner.getSelectedItem().toString(), "0", userId});
            Toast.makeText(this, "Привычка добавлена!", Toast.LENGTH_SHORT).show();
            db.close();

            onBackPressed();
        });
    }

    // Открытие диалога выбора иконки
    private void openIconSelectionDialog() {
        int[] iconResIds = {
                R.drawable.default_icon,  R.drawable.ic_study, R.drawable.ic_work, R.drawable.ic_fitness, R.drawable.ic_meditation, R.drawable.ic_walk,  R.drawable.ic_water, R.drawable.ic_diet, R.drawable.ic_pill, R.drawable.ic_reading, R.drawable.ic_sleep, R.drawable.ic_clean
        }; // Массив иконок

        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Выберите иконку");

        // Для диалога адаптер сетки
        GridView gridView = new GridView(this);
        gridView.setNumColumns(3); // сколько колонок в сетке
        gridView.setAdapter(new IconAdapter(this, iconResIds));

        gridView.setOnItemClickListener((parent, view, position, id) -> {
            selectedIconResId = iconResIds[position]; // Сохраняем выбранный ID иконки
            selectedIconView.setImageResource(selectedIconResId); // Обновляем ImageView
            Toast.makeText(this, "Иконка выбрана!", Toast.LENGTH_SHORT).show();
        });

        builder.setView(gridView);
        builder.setNegativeButton("Закрыть", (dialog, which) -> dialog.dismiss());
        builder.show();
    }

    // Адаптер для отображения иконок в GridView
    private static class IconAdapter extends BaseAdapter {
        private final Context context;
        private final int[] icons;

        public IconAdapter(Context context, int[] icons) {
            this.context = context;
            this.icons = icons;
        }

        @Override
        public int getCount() {
            return icons.length;
        }

        @Override
        public Object getItem(int position) {
            return icons[position];
        }

        @Override
        public long getItemId(int position) {
            return position;
        }

        public View getView(int position, View convertView, ViewGroup parent) {
            ImageView imageView;
            if (convertView == null) {
                imageView = new ImageView(context);
                imageView.setLayoutParams(new GridView.LayoutParams(200, 200)); // Размер иконок
                imageView.setScaleType(ImageView.ScaleType.CENTER_CROP);
            } else {
                imageView = (ImageView) convertView;
            }
            imageView.setImageResource(icons[position]);
            return imageView;
        }
    }
}