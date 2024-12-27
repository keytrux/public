package com.example.egradebook;

import android.content.Intent;
import android.database.sqlite.SQLiteDatabase;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;


public class Begin extends AppCompatActivity {

    // Экземпляр DatabaseHelper
    private DatabaseHelper dbHelper;

    Button btnStudent, btnTeacher;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_begin);

        // Инициализация DatabaseHelper
        dbHelper = new DatabaseHelper(this);

        btnStudent = findViewById(R.id.btnStudent); //кнопка выгрузки
        btnTeacher = findViewById(R.id.btnTeacher); // кнопка редактирования

//        SQLiteDatabase db = dbHelper.getWritableDatabase(); // Открываем базу данных для записи

        //// SQL-запрос на добавление пользователя
//        String insertQuery1 = "INSERT INTO group_list (name_group, course) VALUES ('Вп-21', " + 2 + ")";
//        String insertQuery2 = "INSERT INTO group_list (name_group, course) VALUES ('Вп-31', " + 3 + ")";
//        String insertQuery3 = "INSERT INTO group_list (name_group, course) VALUES ('Вп-41', " + 4 + ")";
//        db.execSQL(insertQuery1); // Выполняем запрос
//        db.execSQL(insertQuery2); // Выполняем запрос
//        db.execSQL(insertQuery3); // Выполняем запрос

//        db.close(); // Закрываем базу данных

        btnStudent.setOnClickListener(new View.OnClickListener() { //нажатие кнопки Я студент
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Begin.this, LoginStudent.class);
                startActivity(intent);
            }

        });

        btnTeacher.setOnClickListener(new View.OnClickListener() { //нажатие кнопки Я студент
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Begin.this, LoginTeacher.class);
                startActivity(intent);
            }

        });

    }


}
