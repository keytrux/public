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

public class FunctionActivity extends AppCompatActivity {

    RecyclerView recyclerView;
    List<DataClass> dataList;
    MyAdapter adapter;
    DataClass androidData;
    SearchView searchView;

    TextView title;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_function);

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

        GridLayoutManager gridLayoutManager = new GridLayoutManager(FunctionActivity.this, 1);
        recyclerView.setLayoutManager(gridLayoutManager);
        dataList = new ArrayList<>();

        androidData = new DataClass("Описание функций", R.string.function_text, R.drawable.function_img);
        dataList.add(androidData);

        androidData = new DataClass("abs()", R.string.while_text, R.drawable.abs);
        dataList.add(androidData);

        androidData = new DataClass("callable()", R.string.callable, R.drawable.callable);
        dataList.add(androidData);

        androidData = new DataClass("chr()", R.string.chr, R.drawable.chr);
        dataList.add(androidData);

        androidData = new DataClass("complex()", R.string.complex, R.drawable.complex);
        dataList.add(androidData);

        androidData = new DataClass("dict()", R.string.dict, R.drawable.dict);
        dataList.add(androidData);

        androidData = new DataClass("dir()", R.string.dir, R.drawable.dir);
        dataList.add(androidData);

        androidData = new DataClass("enumerate()", R.string.enumerate, R.drawable.enumerate);
        dataList.add(androidData);

        androidData = new DataClass("eval()", R.string.eval, R.drawable.eval);
        dataList.add(androidData);

        androidData = new DataClass("filter()", R.string.filter, R.drawable.filter);
        dataList.add(androidData);

        androidData = new DataClass("float()", R.string.float_text, R.drawable.float_img);
        dataList.add(androidData);

        androidData = new DataClass("hash()", R.string.hash, R.drawable.hash);
        dataList.add(androidData);

        androidData = new DataClass("help()", R.string.help, R.drawable.help);
        dataList.add(androidData);

        androidData = new DataClass("input()", R.string.input, R.drawable.input);
        dataList.add(androidData);

        androidData = new DataClass("int()", R.string.int_text, R.drawable.int_img);
        dataList.add(androidData);

        androidData = new DataClass("iter()", R.string.iter, R.drawable.iter);
        dataList.add(androidData);

        androidData = new DataClass("len()", R.string.len, R.drawable.len);
        dataList.add(androidData);

        androidData = new DataClass("list()", R.string.list, R.drawable.list_f);
        dataList.add(androidData);

        androidData = new DataClass("max()", R.string.max, R.drawable.max);
        dataList.add(androidData);

        androidData = new DataClass("min()", R.string.min, R.drawable.min);
        dataList.add(androidData);

        androidData = new DataClass("map()", R.string.map, R.drawable.map);
        dataList.add(androidData);

        androidData = new DataClass("next()", R.string.next, R.drawable.next);
        dataList.add(androidData);

        androidData = new DataClass("ord()", R.string.ord, R.drawable.ord);
        dataList.add(androidData);

        androidData = new DataClass("reversed()", R.string.reversed, R.drawable.reversed);
        dataList.add(androidData);

        androidData = new DataClass("range()", R.string.range, R.drawable.range);
        dataList.add(androidData);

        androidData = new DataClass("reduce()", R.string.reduce, R.drawable.reduce);
        dataList.add(androidData);

        androidData = new DataClass("sorted()", R.string.sorted, R.drawable.sorted);
        dataList.add(androidData);

        androidData = new DataClass("str()", R.string.str, R.drawable.str);
        dataList.add(androidData);

        androidData = new DataClass("set()", R.string.set, R.drawable.set);
        dataList.add(androidData);

        androidData = new DataClass("sum()", R.string.sum, R.drawable.sum);
        dataList.add(androidData);

        androidData = new DataClass("tuple()", R.string.tuple, R.drawable.tuple);
        dataList.add(androidData);

        androidData = new DataClass("type()", R.string.type, R.drawable.type);
        dataList.add(androidData);


        adapter = new MyAdapter(FunctionActivity.this, dataList);
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