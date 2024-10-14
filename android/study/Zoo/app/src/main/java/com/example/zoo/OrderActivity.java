package com.example.zoo;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

public class OrderActivity extends AppCompatActivity {

    String[] addresses = {"vika20072003@mail.ru"};
    String subject = "Order from Zoo shop";
    String emailText;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_order);

        Intent receiveOrderIntent = getIntent();
        String userName = receiveOrderIntent.getStringExtra("userNameForIntent");
        String goodsName = receiveOrderIntent.getStringExtra("goodsName");
        int quantity = receiveOrderIntent.getIntExtra("quantity", 0);
        double orderPrice = receiveOrderIntent.getDoubleExtra("orderPrice", 0);
        TextView orderTextView = findViewById(R.id.orderTextView);
        emailText = "Custoomer name: " + userName + "\n" + goodsName + "\n" + quantity + "\n" + orderPrice;
        orderTextView.setText(emailText);

    }
    public void submitOrder(View view)
    {
         Intent intent = new Intent(Intent.ACTION_SENDTO);
         intent.setData(Uri.parse("mailto"));
         intent.putExtra(Intent.EXTRA_EMAIL,addresses);
         intent.putExtra(Intent.EXTRA_SUBJECT, subject);
         intent.putExtra(Intent.EXTRA_TEXT, emailText);
         if (intent.resolveActivity(getPackageManager()) != null)
         {
             startActivity(intent);
         }
    }
}