package com.stmvideo.webtv.webtv_nome;

import android.app.Dialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;
import android.net.Uri;
import android.os.Bundle;
import androidx.fragment.app.Fragment;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.cast.Casty;
import com.cast.MediaData;
import com.stmvideo.util.AlertDialogManager;
import com.stmvideo.util.Constant;
import com.stmvideo.util.JsonUtils;
import com.stmvideo.util.NetCheck;

public class HomeFragment extends Fragment {

    TextView txt_title;
    WebView webdesc;
    ImageView img_playtv;
    String Tvurl;
    AlertDialogManager alert = new AlertDialogManager();
    JsonUtils jsonUtils;
    private Casty casty;
    ImageView image_close;
    ImageView imageView;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_home, container, false);

        casty = Casty.create(requireActivity())
                .withMiniController();

        jsonUtils = new JsonUtils(getActivity());
        jsonUtils.forceRTLIfSupported(getActivity().getWindow());
        int columnWidth = jsonUtils.getScreenWidth();

        txt_title = rootView.findViewById(R.id.text_title);
        webdesc = rootView.findViewById(R.id.desweb);
        img_playtv = rootView.findViewById(R.id.imageView_play_home_fragment);
        imageView = rootView.findViewById(R.id.image_home_fragment);
        View view = rootView.findViewById(R.id.view_home_fragment);

        imageView.setLayoutParams(new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, columnWidth / 2 + 60));
        view.setLayoutParams(new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, columnWidth / 2 + 60));

        webdesc.setBackgroundColor(Color.WHITE);
        webdesc.setFocusableInTouchMode(false);
        webdesc.setFocusable(false);
        webdesc.setLayerType(View.LAYER_TYPE_SOFTWARE, null);
        webdesc.getSettings().setDefaultTextEncodingName("UTF-8");

        boolean isRTL = Boolean.parseBoolean(getResources().getString(R.string.isRTL));
        String direction = isRTL ? "rtl" : "ltr";
        String text = "<html dir=" + direction + "><head>" + "<style type=\"text/css\">@font-face {font-family: MyFont;src: url(\"file:///android_asset/fonts/Montserrat-Medium_0.otf\")}body,* {font-family: MyFont; color:#5B5B5B; font-size: 15px;line-height:1.6}img{max-width:100%;height:auto; border-radius: 3px;}</style></head></html>";
        webdesc.loadDataWithBaseURL("", text + "<div>" + getString(R.string.channel_description) + "</div>", "text/html", "utf-8", null);

        Typeface tf = Typeface.createFromAsset(getActivity().getAssets(), "fonts/Montserrat-SemiBold_0.otf");
        txt_title.setTypeface(tf);

        imageView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                    if (casty.isConnected()) {
                        playViaCast();
                    } else {
                        showSelectDialog();
                    }
            }
        });

        img_playtv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                    if (casty.isConnected()) {
                        playViaCast();
                    } else {
                        showSelectDialog();
                    }

            }
        });

        setHasOptionsMenu(true);
        return rootView;
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.menu_home_fragment, menu);
        casty.addMediaRouteMenuItem(menu);
        super.onCreateOptionsMenu(menu, inflater);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {

            // action with ID action_refresh was selected
            default:
                break;
        }

        return super.onOptionsItemSelected(item);
    }

    private void showSelectDialog() {
        final Dialog mDialog = new Dialog(requireActivity(), R.style.Theme_AppCompat_Translucent);
        mDialog.setContentView(R.layout.selection_dialog);

        TextView btn_app = mDialog.findViewById(R.id.btn_app);
        TextView btn_external = mDialog.findViewById(R.id.btn_external);
        RelativeLayout relativeLayout = mDialog.findViewById(R.id.relativeLayout_select_dialog);
        image_close = mDialog.findViewById(R.id.image_close);

        image_close.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mDialog.dismiss();
            }
        });

        btn_app.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mDialog.dismiss();
                if (NetCheck.isNetworkAvailable(requireActivity())) {

                    Tvurl = getString(R.string.channel_url);
                    Intent inttv = new Intent(getActivity(), TvPlay.class);
                    inttv.putExtra("url", Tvurl);
                    startActivity(inttv);

                } else {
                    alert.showAlertDialog(getActivity(), getResources().getString(R.string.internet_connection_error),
                            getResources().getString(R.string.please_connect_to_working_internet_connection), false);
                }
            }
        });

        btn_external.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mDialog.dismiss();
                if (NetCheck.isNetworkAvailable(requireActivity())) {

                    Tvurl = getString(R.string.channel_url);
					
					String Tvurl_http = Tvurl.replace("rtmp", "http");
					String Tvurl_httpFinal = Tvurl_http+"/playlist.m3u8";
					
                    Uri intentUri = Uri.parse(Tvurl_httpFinal);
                    Intent intent = new Intent(Intent.ACTION_VIEW, intentUri);
                    intent.setAction(Intent.ACTION_VIEW);
                    intent.setDataAndType(intentUri, "application/x-mpegurl");
                    startActivity(intent);

                } else {
                    alert.showAlertDialog(getActivity(), getResources().getString(R.string.internet_connection_error),
                            getResources().getString(R.string.please_connect_to_working_internet_connection), false);
                }
            }
        });


        mDialog.show();
    }

    private void playViaCast() {
        Tvurl = getString(R.string.channel_url);
        casty.getPlayer().loadMediaAndPlay(createSampleMediaData(Tvurl, getString(R.string.channel_name)));

    }

    private MediaData createSampleMediaData(String videoUrl, String videoTitle) {
        return new MediaData.Builder(videoUrl)
                .setStreamType(MediaData.STREAM_TYPE_BUFFERED)
                .setContentType(getType(videoUrl))
                .setMediaType(MediaData.MEDIA_TYPE_MOVIE)
                .setTitle(videoTitle)
                .setSubtitle(getString(R.string.app_name))
                .build();
    }

    private String getType(String videoUrl) {
        if (videoUrl.endsWith(".mp4")) {
            return "video/mp4";
        } else if (videoUrl.endsWith(".m3u8")) {
            return "application/x-mpegurl";
        } else {
            return "videos/x-flv";
        }
    }

}
