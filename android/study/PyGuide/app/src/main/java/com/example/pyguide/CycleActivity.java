package com.example.pyguide;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.TextView;
import android.widget.Toast;

import com.example.pyguide.databinding.ActivityIntroductionBinding;
import com.example.pyguide.databinding.ActivityMenuBinding;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class CycleActivity extends AppCompatActivity {

    RecyclerView recyclerView;
    List<DataClass> dataList;
    MyAdapter adapter;
    DataClass androidData;
    SearchView searchView;

    TextView title;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_cycle);

        Intent intent = getIntent();
        String Title = intent.getStringExtra("Title");

        title = findViewById(R.id.title);
        title.setText(Title);

        recyclerView = findViewById(R.id.recyclerView);
        searchView = findViewById(R.id.search);

        searchView.clearFocus();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                searchList(newText);
                return true;
            }
        });

        GridLayoutManager gridLayoutManager = new GridLayoutManager(CycleActivity.this, 1);
        recyclerView.setLayoutManager(gridLayoutManager);
        dataList = new ArrayList<>();

        androidData = new DataClass("Цикл for", R.string.for_text, R.drawable.for_img);
        dataList.add(androidData);

        androidData = new DataClass("Цикл while", R.string.while_text, R.drawable.while_img);
        dataList.add(androidData);

        androidData = new DataClass("Конструкция if", R.string.if_text, R.drawable.if_img);
        dataList.add(androidData);

        androidData = new DataClass("Конструкция if-else", R.string.if_else_text, R.drawable.if_else_img);
        dataList.add(androidData);

        androidData = new DataClass("Конструкция if-elif-else", R.string.if_elif_else_text, R.drawable.if_elif_else_img);
        dataList.add(androidData);

        adapter = new MyAdapter(CycleActivity.this, dataList);
        recyclerView.setAdapter(adapter);

    }

    private void searchList(String text)
    {
        List<DataClass> dataSearchList = new ArrayList<>();
        for (DataClass data : dataList)
        {
            if(data.getDataTitle().toLowerCase().contains(text.toLowerCase()))
            {
                dataSearchList.add(data);
            }
        }
        if(dataSearchList.isEmpty())
        {
            Toast.makeText(this, "Не найдено", Toast.LENGTH_SHORT).show();
        }
        else {
            adapter.setSearchList(dataSearchList);
        }
    }

}