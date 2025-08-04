package com.stmvideo.webtv.webtv_nome;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.pm.ActivityInfo;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Build;
import android.os.Bundle;
import android.support.design.widget.CoordinatorLayout;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.content.Intent;
import android.net.Uri;
import android.graphics.Color;
import android.view.WindowManager;

public class Player extends AppCompatActivity {

    private Context context;
    private Activity activity;
    private CoordinatorLayout coordinatorLayout;
    private WebView webView;

    @SuppressLint({"SetJavaScriptEnabled", "ObsoleteSdkInt"})
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_player);
        getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);

        context = getApplicationContext();
        activity = this;

        coordinatorLayout = findViewById(R.id.cl_webView);

        webView = findViewById(R.id.wv_nyoloWeb);

        Player.this.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);      

        loadPlayer();

    }

    private void loadPlayer() {

        webView.setWebViewClient(new WebViewClient() {

            @Override
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                super.onReceivedError(view, errorCode, description, failingUrl);
                webView.loadUrl("file:///android_asset/offline.html");
            }

        });

        String url = getResources().getString(R.string.url_player);

        webView.setBackgroundColor(Color.parseColor("#222222"));
        webView.loadUrl(url+"/play");
        webView.setWebChromeClient(new WebChromeClient());

        WebSettings settings = webView.getSettings();
        settings.setJavaScriptEnabled(true);
        settings.setDomStorageEnabled(true);
        settings.setCacheMode(WebSettings.LOAD_NO_CACHE);
        settings.setUserAgentString("App Android Web by cesarlwh@gmail.com / ExoPlayer 1.0");
    }

    public void onBackPressed() {
        finish();
    }
}
