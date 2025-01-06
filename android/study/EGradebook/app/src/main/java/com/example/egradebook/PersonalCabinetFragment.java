package com.example.egradebook;

import static android.content.Context.MODE_PRIVATE;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Build;
import android.os.Bundle;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.fragment.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import java.time.LocalDate;

public class PersonalCabinetFragment extends Fragment {
    Button exit;

    String fullName, phone, email, id_group, name_group, course, specialization, semester;


    private DatabaseHelper dbHelper;
    @RequiresApi(api = Build.VERSION_CODES.O)
    @SuppressLint({"SetTextI18n", "Range"})
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        dbHelper = new DatabaseHelper(requireActivity());

        View view = inflater.inflate(R.layout.fragment_personal_cabinet, container, false);

        exit = view.findViewById(R.id.button_example);

        exit.setOnClickListener(new View.OnClickListener() { //нажатие кнопки выйти
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View view) {

                SharedPreferences sharedPreferences = requireActivity().getSharedPreferences("StudentPrefs", MODE_PRIVATE);
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.clear(); // Удаляем все сохраненные данные
                editor.apply();

                // открываем начальную активность
                Intent intent = new Intent(requireActivity(), Begin.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivity(intent);
                requireActivity().finish(); // Закрываем текущую активность
            }
        });

        // получаем данные по студенту через id_user
        SharedPreferences sharedPreferences = requireActivity().getSharedPreferences("StudentPrefs", MODE_PRIVATE);
        Integer id_user = sharedPreferences.getInt("id_user", 0);

        SQLiteDatabase db = dbHelper.getWritableDatabase();

        String selectStudent = "SELECT * FROM students WHERE id_user = ?";
        Cursor cursorStudent = db.rawQuery(selectStudent, new String[]{String.valueOf(id_user)});

        String selectUser = "SELECT * FROM users WHERE id_user = ?";
        Cursor cursorUser = db.rawQuery(selectUser, new String[]{String.valueOf(id_user)});
        if (cursorStudent.moveToFirst()) {
            fullName = cursorStudent.getString(cursorStudent.getColumnIndex("full_name"));
            id_group = cursorStudent.getString(cursorStudent.getColumnIndex("id_group"));

            String selectGroup = "SELECT * FROM group_list WHERE id_group = ?";
            Cursor cursorGroup = db.rawQuery(selectGroup, new String[]{id_group});

            if (cursorGroup.moveToFirst()) {
                specialization = cursorGroup.getString(cursorGroup.getColumnIndex("specialization"));
                name_group = cursorGroup.getString(cursorGroup.getColumnIndex("name_group"));
                course = cursorGroup.getString(cursorGroup.getColumnIndex("course"));
            }

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

                TextView fullNameTextView = view.findViewById(R.id.fullNameTextView);
                TextView specializationTextView = view.findViewById(R.id.specializationTextView);
                TextView nameGroupTextView = view.findViewById(R.id.nameGroupTextView);
                TextView courseTextView = view.findViewById(R.id.courseTextView);
                TextView semesterTextView = view.findViewById(R.id.semesterTextView);
                TextView phoneTextView = view.findViewById(R.id.phoneTextView);
                TextView emailTextView = view.findViewById(R.id.emailTextView);

                fullNameTextView.setText("ФИО: " + fullName);
                specializationTextView.setText("Специальность: " + specialization);
                nameGroupTextView.setText("Группа: " + name_group);
                courseTextView.setText("Курс: " + course);
                semesterTextView.setText("Семестр: " + semester);
                phoneTextView.setText("Телефон: " + phone);
                emailTextView.setText("Email: " + email);
            }
        }

        if (cursorStudent != null) {
            cursorStudent.close(); // Закрываем cursorStudent
        }
        if (cursorUser != null) {
            cursorUser.close(); // Закрываем cursorUser
        }

        db.close();

        return view; // Вернуть созданное представление
    }
}
