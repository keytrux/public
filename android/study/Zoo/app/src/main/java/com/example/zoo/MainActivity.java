package com.example.zoo;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;

import org.w3c.dom.Text;

import java.util.ArrayList;
import java.util.HashMap;

public class MainActivity extends AppCompatActivity implements AdapterView.OnItemSelectedListener{

    int quantity = 0;
    int price;
    String goodsName;
    Spinner spinner;
    ArrayList spinnerArrayList;
    ArrayAdapter spinnerAdapter;
    HashMap<String, Integer> goodsMap;
    EditText userNameEditText;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        createSpinner();
        createGoodsMap();
        userNameEditText = findViewById(R.id.plain_text_input);
    }
    void createSpinner()
    {
        spinner = findViewById(R.id.spinner);
        spinner.setOnItemSelectedListener(this);
        spinnerArrayList = new ArrayList();

        spinnerArrayList.add("food");
        spinnerArrayList.add("toys");
        spinnerArrayList.add("beds");

        spinnerAdapter = new ArrayAdapter(this, android.R.layout.simple_spinner_item, spinnerArrayList);
        spinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(spinnerAdapter);
    }
    void createGoodsMap()
    {
        goodsMap = new HashMap();
        goodsMap.put("food", 200);
        goodsMap.put("toys", 150);
        goodsMap.put("beds", 400);
    }

    public void increaseQuantity(View view) {
        quantity++;
        TextView in = findViewById(R.id.textView7);
        in.setText("" + quantity);
        TextView textview5 = findViewById(R.id.textView5);
        textview5.setText("Price: " + quantity * price);
    }
    public void decreaseQuantity(View view) {
        if (quantity > 0)
        {
            quantity--;
            TextView de = findViewById(R.id.textView7);
            de.setText("" + quantity);
            TextView textview5 = findViewById(R.id.textView5);
            textview5.setText("Price: " + quantity * price);
        }
    }


    @Override
    public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
        goodsName = spinner.getSelectedItem().toString();
        price = (int)goodsMap.get(goodsName);
        TextView textview5 = findViewById(R.id.textView5);
        textview5.setText("Price: " + quantity * price);

        ImageView goodsImageView = findViewById(R.id.imageView6);
        switch (goodsName) {
            case "food":
                goodsImageView.setImageResource(R.drawable.food);
                break;
            case "toys":
                goodsImageView.setImageResource(R.drawable.toys);
                break;
            case "beds":
                goodsImageView.setImageResource(R.drawable.beds);
                break;
        }
    }

    @Override
    public void onNothingSelected(AdapterView<?> adapterView) {

    }
    public void addToCart(View view)
    {
        Order order = new Order();
//
//        order.userName = userNameEditText.getText().toString();
//        Log.d("userName", order.userName);
//
//        order.goodsName = goodsName;
//        Log.d("goodsName", order.goodsName);
//
//        order.quantity = quantity;
//        Log.d("quantity", "" +  order.quantity);
//
//        order.orderPrice = quantity * price;
//        Log.d("userName", "" + order.orderPrice);
        order.userName = userNameEditText.getText().toString();
        order.goodsName = goodsName;
        order.quantity = quantity;
        order.orderPrice = quantity * price;

        Intent orderIntent = new Intent(MainActivity.this, OrderActivity.class);
        orderIntent.putExtra("userNameForIntent", order.userName);
        orderIntent.putExtra("goodsName", order.goodsName);
        orderIntent.putExtra("quantity", order.quantity);
        orderIntent.putExtra("orderPrice", order.orderPrice);

        startActivity(orderIntent);
    }
}