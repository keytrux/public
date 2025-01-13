package com.example.habitflow;

import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.os.Build;
import android.util.Log;
import androidx.annotation.NonNull;
import androidx.core.app.NotificationCompat;
import androidx.work.Worker;
import androidx.work.WorkerParameters;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

public class HabitReminderWorker extends Worker {

    public HabitReminderWorker(@NonNull Context context, @NonNull WorkerParameters workerParams) {
        super(context, workerParams);
    }

    @NonNull
    @Override
    public Result doWork() {
        // Проверяем привычки и отправляем напоминания
        checkHabits();
        return Result.success();
    }

    private void checkHabits() {
        SharedPreferences preferences = this.getApplicationContext().getSharedPreferences("UserPrefs", Context.MODE_PRIVATE);
        int userId = preferences.getInt("id_user", 0);

        DatabaseHelper dbHelper = new DatabaseHelper(getApplicationContext());
        Cursor cursor = dbHelper.getAllHabits(userId);

        // Получаем даты
        String today = new SimpleDateFormat("dd.MM.yyyy", Locale.getDefault()).format(new Date());
        Calendar calendar = Calendar.getInstance();
        calendar.add(Calendar.DAY_OF_YEAR, -1);

        if (cursor != null) {
            while (cursor.moveToNext()) {
                String habitName = cursor.getString(cursor.getColumnIndexOrThrow(DatabaseHelper.COLUMN_NAME));
                String habitStreak = cursor.getString(cursor.getColumnIndexOrThrow(DatabaseHelper.COLUMN_STREAK));
                String habitGoal = cursor.getString(cursor.getColumnIndexOrThrow(DatabaseHelper.COLUMN_GOAL));
                String lastCompletedDate = cursor.getString(cursor.getColumnIndexOrThrow(DatabaseHelper.COLUMN_LAST_COMPLETED_DATE));

                // Проверка выполнения привычки
                if (!today.equals(lastCompletedDate) && Integer.parseInt(habitStreak) < Integer.parseInt(habitGoal)) {
                    sendReminder(habitName);
                }
            }
            cursor.close();
        }
    }

    private void sendReminder(String habitName) {
        NotificationManager notificationManager = (NotificationManager) getApplicationContext().getSystemService(Context.NOTIFICATION_SERVICE);
        String channelId = "habit_channel";

        // Проверка, существует ли канал уведомлений
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                    channelId, "Habits Reminder", NotificationManager.IMPORTANCE_DEFAULT
            );
            if (notificationManager != null) {
                notificationManager.createNotificationChannel(channel);
            }
        }

        // Создание уведомления
        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(getApplicationContext(), channelId)
                .setSmallIcon(R.drawable.ic_notification) // Замените на ваш значок
                .setContentTitle("Напоминание")
                .setContentText("Вы не выполнили привычку: " + habitName)
                .setPriority(NotificationCompat.PRIORITY_HIGH)
                .setAutoCancel(true);

        // Создание PendingIntent для уведомления, указывая конечную активность
        Intent intent = new Intent(getApplicationContext(), Screensaver.class); // Укажите конечную активность
        PendingIntent pendingIntent = PendingIntent.getActivity(getApplicationContext(), 0, intent, PendingIntent.FLAG_IMMUTABLE);
        notificationBuilder.setContentIntent(pendingIntent);

        // Отправка уведомления
        if (notificationManager != null) {
            notificationManager.notify(habitName.hashCode(), notificationBuilder.build());
        }

        Log.d("HabitReminder", "Напоминание: Вы не выполнили привычку: " + habitName);
    }
}