package com.example.egradebook;

import android.annotation.SuppressLint;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import java.util.ArrayList;
import java.util.List;

public class Practices extends AppCompatActivity {

    DatabaseHelper dbHelper;
    String id_student;

    @SuppressLint("Range")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_practices);

        TableLayout tablePartices = this.findViewById(R.id.table_practices);

        displayGrades(tablePartices); // вызываем метод для вывод данных о практик в таблицу
    }

    // метод для вывода данных
    @SuppressLint("Range")
    private void displayGrades(TableLayout tablePartices) {
        tablePartices.removeAllViews();

        List<String[]> particesList = new ArrayList<>();

        dbHelper = new DatabaseHelper(this);

        SQLiteDatabase db = dbHelper.getWritableDatabase();

        SharedPreferences sharedPreferences = this.getSharedPreferences("StudentPrefs", MODE_PRIVATE);
        Integer id_user = sharedPreferences.getInt("id_user", 0); // берем id пользователя из сохранённых данных

        String selectStudent = "SELECT * FROM students WHERE id_user = ?"; // запрос на получение студента по id_user
        Cursor cursorStudent = db.rawQuery(selectStudent, new String[]{String.valueOf(id_user)});

        if (cursorStudent.moveToFirst()) {
            id_student = cursorStudent.getString(cursorStudent.getColumnIndex("id_student")); // сохраняем найденный id_student
        }

        String selectGrade = "SELECT g.grade, g.date, w.course, w.semester, w.name_work, w.type FROM gradebooks g JOIN works w ON g.id_work = w.id_work" +
                " WHERE g.id_student = ? AND w.type = ?" +
                " ORDER BY w.course, w.semester"; // запрос на получение курсовых и данных из зачетки
        Cursor cursorGrade = db.rawQuery(selectGrade, new String[]{String.valueOf(id_student), "Практика"});

        if (cursorGrade.moveToFirst()) {
            do {
                String date = cursorGrade.getString(cursorGrade.getColumnIndex("date")); // дата
                String name_work = cursorGrade.getString(cursorGrade.getColumnIndex("name_work")); // название работы
                String course = cursorGrade.getString(cursorGrade.getColumnIndex("course")); // курс
                String semester = cursorGrade.getString(cursorGrade.getColumnIndex("semester")); // семестр
                String grade = cursorGrade.getString(cursorGrade.getColumnIndex("grade")); // оценка

                String[] partices = {name_work, date, course, semester, grade}; // сохраняем в массив данные
                particesList.add(partices);
            }
            while (cursorGrade.moveToNext());
        }
        else {
            Toast.makeText(this, "Нет записей!", Toast.LENGTH_SHORT).show();
        }

        if (cursorStudent != null) {
            cursorStudent.close(); // Закрываем cursorStudent
        }
        if (cursorGrade != null) {
            cursorGrade.close(); // Закрываем cursorGrade
        }

        db.close();

        // Добавление заголовков для таблицы
        TableRow headerRowExam = new TableRow(this);
        String[] examHeaders = {"Название", "Дата", "Курс", "Семестр", "Оценка"};

        // Создаем 1 строку в таблицу с заголовками
        for (String header : examHeaders) {
            TextView textView = new TextView(this);
            textView.setText(header);
            textView.setPadding(10, 10, 10, 10); // отступы между столбцами
            textView.setBackgroundColor(Color.LTGRAY); // заливка
            headerRowExam.addView(textView);
        }

        addSeparatorLine(tablePartices); // добавляем разделитель
        tablePartices.addView(headerRowExam); // добавляем в таблицу строку с заголовками
        addSeparatorLine(tablePartices); // добавляем разделитель

        // Заполнение таблицы практик
        for (String[] exam : particesList) {
            TableRow row = new TableRow(this);

            for (String data : exam) {
                TextView textView = new TextView(this);
                textView.setText(data);
                textView.setPadding(10, 10, 10, 10); // отступы между столбцами
                row.addView(textView);
            }

            tablePartices.addView(row); // добавляем в таблицу строку
            addSeparatorLine(tablePartices); // добавляем разделитель
        }
        addSeparatorLine(tablePartices); // добавляем разделитель
    }

    // метод для создания разделителя
    private void addSeparatorLine(TableLayout tableLayout) {
        View line = new View(this);
        line.setLayoutParams(new TableRow.LayoutParams(
                TableRow.LayoutParams.MATCH_PARENT, 1)); // высота 1 пиксель
        line.setBackgroundColor(Color.BLACK); // цвет линии
        tableLayout.addView(line);
    }
}
