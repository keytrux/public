package com.example.electrotechub;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.fragment.app.Fragment;


public class AccountFragment extends Fragment {

    TextView writeName, writeNumber;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

//
//
//
//        showUserData();

    }
    public void showUserData()
    {
        writeName = getView().findViewById(R.id.writeName);
        writeNumber = getView().findViewById(R.id.writeNumber);
        Intent intent = getActivity().getIntent();

        String nameUser = intent.getStringExtra("name");
        String numberUser = intent.getStringExtra("number");

        writeName.setText(nameUser);
        writeNumber.setText(numberUser);
    }
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_account, container, false);

        writeName = view.findViewById(R.id.writeName);
        writeNumber = view.findViewById(R.id.writeNumber);
        Intent intent = getActivity().getIntent();

        String nameUser = intent.getStringExtra("name");
        String numberUser = intent.getStringExtra("number");

        writeName.setText(nameUser);
        writeNumber.setText(numberUser);

        TextView exit = view.findViewById(R.id.textExit);
        exit.setOnClickListener(new View.OnClickListener()
        {
            @Override
            public void onClick(View v)
            {
                Intent intent = new Intent(AccountFragment.this.getActivity(), LoginActivity.class);
                startActivity(intent);
            }
        });

        return view;
    }


    public void exit(View view) {
        Intent intent = new Intent(AccountFragment.this.getActivity(), SignIn.class);
        startActivity(intent);
    }


}
