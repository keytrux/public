package com.example.egradebook;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;


public class Begin extends AppCompatActivity {

    Button btnStudent, btnTeacher;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_begin);

        btnStudent = findViewById(R.id.btnStudent); //кнопка выгрузки
        btnTeacher = findViewById(R.id.btnTeacher); // кнопка редактирования

        checkLoginStatusStudent();
        checkLoginStatusTeacher();

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

    private void checkLoginStatusStudent() {
        SharedPreferences sharedPreferences = getSharedPreferences("StudentPrefs", MODE_PRIVATE); // проверяем сохранены ли данные студента
        String phone = sharedPreferences.getString("phone", null);

        if (phone != null) {
            // Если номер телефона сохранен, автоматически перейти в нужную активность
            Intent intent = new Intent(this, StudentPersonalAccount.class);
            startActivity(intent);
            finish(); // Закрываем текущий экран
        }
    }

    private void checkLoginStatusTeacher() {
        SharedPreferences sharedPreferences = getSharedPreferences("TeacherPrefs", MODE_PRIVATE); // проверяем сохранены ли данные преподавателя
        String phone = sharedPreferences.getString("phone", null);

        if (phone != null) {
            // Если номер телефона сохранен, автоматически перейти в нужную активность
            Intent intent = new Intent(this, TeacherPersonalAccount.class);
            startActivity(intent);
            finish(); // Закрываем текущий экран
        }
    }
}
