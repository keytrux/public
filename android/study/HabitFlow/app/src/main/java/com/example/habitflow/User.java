package com.example.habitflow;

import android.os.Bundle;
import android.os.Handler;

import androidx.appcompat.app.AppCompatActivity;
import androidx.navigation.NavController;
import androidx.navigation.fragment.NavHostFragment;
import androidx.navigation.ui.NavigationUI;
import androidx.work.PeriodicWorkRequest;
import androidx.work.WorkManager;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import java.util.concurrent.TimeUnit;

public class User extends AppCompatActivity {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user);

        WorkManager workManager = WorkManager.getInstance(this);
        // Проверяем, существует ли уже WorkRequest с определенным тегом
        workManager.getWorkInfosByTagLiveData("habitReminderTag").observe(this, workInfos -> {
            if (workInfos == null || workInfos.isEmpty()) {
                // Если работ не найдено, создаем новый WorkRequest
                PeriodicWorkRequest habitReminderWork = new PeriodicWorkRequest.Builder(
                        HabitReminderWorker.class, 1, TimeUnit.DAYS
                ).addTag("habitReminderTag").build();

                workManager.enqueue(habitReminderWork);
            }
        });

        // Получаем NavHostFragment из фрагмент-менеджера
        NavHostFragment navHostFragment = (NavHostFragment) getSupportFragmentManager()
                .findFragmentById(R.id.nav_host_fragment);

        // Получаем NavController и связываем его с нижней навигацией
        NavController navController = navHostFragment.getNavController();
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottomNavigationView);
        NavigationUI.setupWithNavController(bottomNavigationView, navController);
    }
}