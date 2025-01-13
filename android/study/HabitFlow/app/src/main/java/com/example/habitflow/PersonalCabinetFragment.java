package com.example.habitflow;

import static android.content.Context.MODE_PRIVATE;
import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.fragment.app.Fragment;
import androidx.work.PeriodicWorkRequest;
import androidx.work.WorkManager;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.concurrent.TimeUnit;

public class PersonalCabinetFragment extends Fragment {

    private DatabaseHelper dbHelper;
    Button exit;
    String name, phone, email, created;

    @SuppressLint("Range")
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_personal_cabinet, container, false);

        WorkManager workManager = WorkManager.getInstance(getActivity());
        // Проверяем, существует ли уже WorkRequest с определенным тегом
        workManager.getWorkInfosByTagLiveData("habitReminderTag").observe(getActivity(), workInfos -> {
            if (workInfos == null || workInfos.isEmpty()) {
                // Если работ не найдено, создаем новый WorkRequest
                PeriodicWorkRequest habitReminderWork = new PeriodicWorkRequest.Builder(
                        HabitReminderWorker.class, 1, TimeUnit.DAYS
                ).addTag("habitReminderTag").build();

                workManager.enqueue(habitReminderWork);
            }
        });

        // Получаем текущую дату и вчерашнюю дату
        String today = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(new Date());
        Calendar calendar = Calendar.getInstance();
        calendar.add(Calendar.DAY_OF_YEAR, -1);
        String yesterday = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(calendar.getTime());

        dbHelper = new DatabaseHelper(requireActivity());

        exit = view.findViewById(R.id.button_exit);

        exit.setOnClickListener(new View.OnClickListener() { //нажатие кнопки выйти
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View view) {
                SharedPreferences sharedPreferences = requireActivity().getSharedPreferences("UserPrefs", MODE_PRIVATE);
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.clear(); // Удаляем все сохраненные данные
                editor.apply();

                // открываем начальную активность
                Intent intent = new Intent(requireActivity(), LoginRegister.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivity(intent);
                requireActivity().finish(); // Закрываем текущую активность
            }
        });

        // получаем данные пользователя через сохраненный id_user
        SharedPreferences sharedPreferences = requireActivity().getSharedPreferences("UserPrefs", MODE_PRIVATE);
        Integer id_user = sharedPreferences.getInt("id_user", 0);

        SQLiteDatabase db = dbHelper.getWritableDatabase();

        String selectUser = "SELECT * FROM users WHERE id_user = ?";
        Cursor cursorUser = db.rawQuery(selectUser, new String[]{String.valueOf(id_user)});

        if (cursorUser.moveToFirst()) {
            name = cursorUser.getString(cursorUser.getColumnIndex("name"));
            phone = cursorUser.getString(cursorUser.getColumnIndex("phone"));
            email = cursorUser.getString(cursorUser.getColumnIndex("email"));
            created = cursorUser.getString(cursorUser.getColumnIndex("created"));

            TextView fullNameTextView = view.findViewById(R.id.fullNameTextView);
            TextView phoneTextView = view.findViewById(R.id.phoneTextView);
            TextView emailTextView = view.findViewById(R.id.emailTextView);
            TextView createdTextView = view.findViewById(R.id.createdTextView);

            fullNameTextView.setText("Имя: " + name);
            phoneTextView.setText("Телефон: " + phone);
            emailTextView.setText("Email: " + email);
            createdTextView.setText("Дата регистрации: " + created);
        }
        return view;
    }
}