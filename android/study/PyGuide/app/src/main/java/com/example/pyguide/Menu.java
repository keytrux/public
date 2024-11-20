package com.example.pyguide;

import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

public class Menu extends AppCompatActivity {
    CardView introduction, variables, cycle, function;
    TextView introduction_txt, variables_txt, cycle_txt, function_txt;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);

        introduction = findViewById(R.id.introduction);

        introduction.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent introduction = new Intent(Menu.this, introduction.class);
                introduction_txt = findViewById(R.id.introduction_txt);
                introduction.putExtra("Title", introduction_txt.getText());
                startActivity(introduction);
            }
        });

        variables = findViewById(R.id.variables);
        variables.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent variables = new Intent(Menu.this, VariablesActivity.class);
                variables_txt = findViewById(R.id.variables_txt);
                variables.putExtra("Title", variables_txt.getText());
                startActivity(variables);
            }
        });

        cycle = findViewById(R.id.cycle);
        cycle.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent cycle = new Intent(Menu.this, CycleActivity.class);
                cycle_txt = findViewById(R.id.cycle_txt);
                cycle.putExtra("Title", cycle_txt.getText());
                startActivity(cycle);
            }
        });

        function = findViewById(R.id.function);
        function.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent function = new Intent(Menu.this, FunctionActivity.class);
                function_txt = findViewById(R.id.function_txt);
                function.putExtra("Title", function_txt.getText());
                startActivity(function);
            }
        });
    }
}