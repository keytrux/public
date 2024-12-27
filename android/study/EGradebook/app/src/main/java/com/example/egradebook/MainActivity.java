package com.example.egradebook; // Замените на ваш пакет

import androidx.appcompat.app.AppCompatActivity;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity {

    // Экземпляр DatabaseHelper
    private DatabaseHelper dbHelper;

    EditText name, ageEditText;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Инициализация DatabaseHelper
        dbHelper = new DatabaseHelper(this);

        // Подключение кнопок
        Button btnAddUser = findViewById(R.id.btn_add_user);       // Кнопка добавления пользователя
        Button btnShowUsers = findViewById(R.id.btn_show_users);   // Кнопка отображения пользователей



        // Нажатие на кнопку добавления пользователя
        btnAddUser.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                name = (EditText) findViewById(R.id.name);
                ageEditText = (EditText) findViewById(R.id.age); // Находим EditText по ID
                String ageString = ageEditText.getText().toString();      // Получаем текст из EditText
                int age = Integer.parseInt(ageString);                   // Преобразуем текст в int

                addUser(name.getText().toString(), age); // Добавление пользователя "Bob" с возрастом 30
            }
        });

        // Нажатие на кнопку отображения всех пользователей
        btnShowUsers.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                readAllUsers(); // Вывод списка всех пользователей
            }
        });

    }

    /**
     * Метод для добавления пользователя в таблицу `users`.
     * @param name Имя пользователя
     * @param age Возраст пользователя
     */
    private void addUser(String name, int age) {
        SQLiteDatabase db = dbHelper.getWritableDatabase(); // Открываем базу данных для записи

        // SQL-запрос на добавление пользователя
        String insertQuery = "INSERT INTO users (name, age) VALUES ('" + name + "', " + age + ")";
        db.execSQL(insertQuery); // Выполняем запрос

        db.close(); // Закрываем базу данных
        Log.d("SQLite", "Пользователь добавлен: имя=" + name + ", возраст=" + age);
    }

    /**
     * Метод для чтения всех пользователей из таблицы `users`.
     */
    private void readAllUsers() {
        SQLiteDatabase db = dbHelper.getReadableDatabase();

        String selectQuery = "SELECT * FROM users";
        Cursor cursor = db.rawQuery(selectQuery, null);

        if (cursor.moveToFirst()) {
            do {
                // Проверяем значения каждого индекса перед его использованием
                int idIndex = cursor.getColumnIndex("id");
                int nameIndex = cursor.getColumnIndex("name");
                int ageIndex = cursor.getColumnIndex("age");

                // Проверяем, что колонки существуют
                if (idIndex >= 0 && nameIndex >= 0 && ageIndex >= 0) {
                    int id = cursor.getInt(idIndex);
                    String name = cursor.getString(nameIndex);
                    int age = cursor.getInt(ageIndex);

                    Log.d("SQLite", "Пользователь: ID=" + id + ", Имя=" + name + ", Возраст=" + age);
                } else {
                    Log.e("SQLite", "Одна или несколько колонок не найдены!");
                }
            } while (cursor.moveToNext());
        } else {
            Log.d("SQLite", "В таблице нет данных.");
        }

        cursor.close();
        db.close();
    }
}
