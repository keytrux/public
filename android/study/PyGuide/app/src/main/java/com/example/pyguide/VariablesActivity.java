package com.example.pyguide;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;

public class VariablesActivity extends AppCompatActivity {

    RecyclerView recyclerView;
    List<DataClass> dataList;
    MyAdapter adapter;
    DataClass androidData;
    SearchView searchView;

    TextView title;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_variables);

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

        GridLayoutManager gridLayoutManager = new GridLayoutManager(VariablesActivity.this, 1);
        recyclerView.setLayoutManager(gridLayoutManager);
        dataList = new ArrayList<>();

        androidData = new DataClass("Переменные", R.string.variables, R.drawable.variables_img);
        dataList.add(androidData);

        androidData = new DataClass("Числа", R.string.integer_text, R.drawable.integer_img);
        dataList.add(androidData);

        androidData = new DataClass("Строки", R.string.string_text, R.drawable.string);
        dataList.add(androidData);

        androidData = new DataClass("Списки", R.string.lists_text, R.drawable.list);
        dataList.add(androidData);

        androidData = new DataClass("Словари", R.string.dictionaries_text, R.drawable.dictionaries);
        dataList.add(androidData);

        androidData = new DataClass("Кортежи", R.string.tuples_text, R.drawable.tuples);
        dataList.add(androidData);

        androidData = new DataClass("Множество", R.string.plenty_text, R.drawable.plenty);
        dataList.add(androidData);

        androidData = new DataClass("Булевы значения", R.string.bool_text, R.drawable.bool);
        dataList.add(androidData);

        adapter = new MyAdapter(VariablesActivity.this, dataList);
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