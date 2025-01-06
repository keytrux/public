package com.example.egradebook;

import static android.content.Context.MODE_PRIVATE;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Build;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.fragment.app.Fragment;

import java.time.LocalDate;

public class PersonalCabinetFragmentTeacher extends Fragment {

    Button exit;

    String fullName, phone, email,  semester;


    private DatabaseHelper dbHelper;

    @RequiresApi(api = Build.VERSION_CODES.O)
    @SuppressLint({"SetTextI18n", "Range", "MissingInflatedId"})
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_personal_cabinet_teacher, container, false);

        dbHelper = new DatabaseHelper(requireActivity());
        exit = view.findViewById(R.id.button_exit_teacher);

        exit.setOnClickListener(new View.OnClickListener() { //нажатие кнопки выйти
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View view) {

                SharedPreferences sharedPreferences = requireActivity().getSharedPreferences("TeacherPrefs", MODE_PRIVATE);
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.clear(); // Удаляем все сохраненные данные
                editor.apply();

                // открываем начальную активность
                Intent intent = new Intent(requireActivity(), Begin.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivity(intent);
                requireActivity().finish(); // Закрываем текущее активити
            }
        });

        // получаем данные по преподавателю через id_user
        SharedPreferences sharedPreferences = requireActivity().getSharedPreferences("TeacherPrefs", MODE_PRIVATE);
        Integer id_user = sharedPreferences.getInt("id_user", 0);

        SQLiteDatabase db = dbHelper.getWritableDatabase();

        String selectTeacher = "SELECT * FROM teachers WHERE id_user = ?";
        Cursor cursorTeacher = db.rawQuery(selectTeacher, new String[]{String.valueOf(id_user)});

        String selectUser = "SELECT * FROM users WHERE id_user = ?";
        Cursor cursorUser = db.rawQuery(selectUser, new String[]{String.valueOf(id_user)});

        if (cursorTeacher.moveToFirst()) {
            fullName = cursorTeacher.getString(cursorTeacher.getColumnIndex("full_name"));

            // получаем настоящий семестр
            LocalDate today = LocalDate.now();
            switch (today.getMonth()) {
                case SEPTEMBER: case OCTOBER: case NOVEMBER: case DECEMBER:
                    semester = "1";
                break;
                default:
                   semester = "2";
                break;
            }

            // выводим полученные данные
            if (cursorUser.moveToFirst()) {
                phone = cursorUser.getString(cursorUser.getColumnIndex("phone"));
                email = cursorUser.getString(cursorUser.getColumnIndex("email"));

                TextView fullNameTextView = view.findViewById(R.id.fullNameTextViewTeacher);
                TextView semesterTextView = view.findViewById(R.id.semesterTextViewTeacher);
                TextView phoneTextView = view.findViewById(R.id.phoneTextViewTeacher);
                TextView emailTextView = view.findViewById(R.id.emailTextViewTeacher);

                fullNameTextView.setText("ФИО: " + fullName);
                semesterTextView.setText("Семестр: " + semester);
                phoneTextView.setText("Телефон: " + phone);
                emailTextView.setText("Email: " + email);
            }
        }
        if (cursorTeacher != null) {
            cursorTeacher.close(); // Закрываем cursorStudent
        }
        if (cursorUser != null) {
            cursorUser.close(); // Закрываем cursorStudent
        }

        db.close();

        return view;
    }
}