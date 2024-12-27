package com.example.egradebook;

import android.os.Bundle;
import android.util.Log;

import androidx.appcompat.app.AppCompatActivity;
import androidx.navigation.NavController;
import androidx.navigation.fragment.NavHostFragment;
import androidx.navigation.ui.NavigationUI;
import com.google.android.material.bottomnavigation.BottomNavigationView;

public class StudentPersonalAccount extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_pa);
        System.out.println("1!!!");
        // Получаем NavHostFragment из фрагмент-менеджера
        NavHostFragment navHostFragment = (NavHostFragment) getSupportFragmentManager()
                .findFragmentById(R.id.nav_host_fragment);

        //if (navHostFragment == null) {
            //System.out.println("ERROR!!!");
            //throw new IllegalStateException("NavHostFragment не найден! Проверьте правильность ID.");

        //}

        // Получаем NavController и связываем его с нижней навигацией
        NavController navController = navHostFragment.getNavController();
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottomNavigationView);
        NavigationUI.setupWithNavController(bottomNavigationView, navController);
    }

}
