    package com.phonegap.MeltsMyHeart;

    import android.app.Activity;
    import android.os.Bundle;
    import com.phonegap.*;

    public class MeltsMyHeart extends DroidGap
    {
        @Override
        public void onCreate(Bundle savedInstanceState)
        {
            super.onCreate(savedInstanceState);
            super.loadUrl("http://meltsmyheart.com");
        }
    }
    
