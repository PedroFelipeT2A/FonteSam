package com.stmvideo.webtv.webtv_nome;

import android.app.Application;
import android.content.Context;
import android.os.Build;
import android.os.StrictMode;
import androidx.multidex.MultiDex;
//import com.onesignal.OneSignal;

import io.github.inflationx.calligraphy3.CalligraphyConfig;
import io.github.inflationx.calligraphy3.CalligraphyInterceptor;
import io.github.inflationx.calligraphy3.FontMapper;
import io.github.inflationx.viewpump.ViewPump;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class MyApplication extends Application {

    public static MyApplication instance;

    public static MyApplication getAppInstance() {
        return instance;
    }

    @Override
    public void onCreate() {
        super.onCreate();
        //MobileAds.initialize(this, getString(R.string.admob_app_id));
        //OneSignal.startInit(this)
        //        .inFocusDisplaying(OneSignal.OSInFocusDisplayOption.Notification)
        //        .init();

        ViewPump.init(ViewPump.builder()
        .addInterceptor(new CalligraphyInterceptor(
                new CalligraphyConfig.Builder()
                    .setDefaultFontPath("fonts/Montserrat-Medium_0.otf")
                    .setFontAttrId(R.attr.fontPath)
                    .build()))
        .build());

        StrictMode.VmPolicy.Builder builder = new StrictMode.VmPolicy.Builder();
        StrictMode.setVmPolicy(builder.build());
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR2) {
            builder.detectFileUriExposure();
        }
     }

    public MyApplication() {
        instance = this;
    }

    @Override
    protected void attachBaseContext(Context base) {
        super.attachBaseContext(base);
        MultiDex.install(this);
    }
}
