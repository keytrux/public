package com.example.habitflow;

import androidx.appcompat.app.AppCompatActivity;
import androidx.work.PeriodicWorkRequest;
import androidx.work.WorkManager;
import androidx.work.WorkRequest;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import java.util.concurrent.TimeUnit;

public class Screensaver extends AppCompatActivity {
    private final int SPLASH_DISPLAY_LENGTH = 5000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.screensaver);

        // Настройка уведомлений
        createNotificationChannel();

        // Запуск "HabitReminderWorker"
        WorkRequest habitReminderRequest = new PeriodicWorkRequest.Builder(HabitReminderWorker.class, 2, TimeUnit.HOURS).build();

        WorkManager.getInstance(this).enqueue(habitReminderRequest);

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                // Переход к LoginRegister
                Intent menu = new Intent(Screensaver.this, LoginRegister.class);
                Screensaver.this.startActivity(menu);
                Screensaver.this.finish();
            }
        }, SPLASH_DISPLAY_LENGTH);
    }

    private void createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            String channelId = "YOUR_CHANNEL_ID";
            String channelName = "YOUR_CHANNEL_NAME";
            int importance = NotificationManager.IMPORTANCE_HIGH;
            NotificationChannel channel = new NotificationChannel(channelId, channelName, importance);
            NotificationManager notificationManager = getSystemService(NotificationManager.class);
            notificationManager.createNotificationChannel(channel);
        }
    }

    @Override
    public void onBackPressed()
    {
        super.onBackPressed();
    }
}