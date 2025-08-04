// by Cesar Fernandes - cesarlwh@gmail.com
import Settings from './Settings.js';
import WowzaWebRTCPlay from './WowzaWebRTCPlay.js';

let state = {
  settings: {
    playSdpURL: "",
    playApplicationName: "",
    playStreamName: ""
  }
};
let statePrefix = 'play';

const init = (errorHandler,connected,stopped) => {
  initListeners();
  window.wowzaWebRTCPlay = new WowzaWebRTCPlay();
  wowzaWebRTCPlay.on({
    onError:errorHandler,
    onStateChanged: (state) => {
      if (state.connectionState === 'connected')
      {
        connected();
      }else {
        stopped();
      }
    }
  });
  wowzaWebRTCPlay.set({
    videoElementPlay:document.getElementById('player-video'),
  });
}

const getState = () => {
  return state;
}

const start = (settings) => {
  update(settings).then(() => {
    wowzaWebRTCPlay.play();
  });
}

const stop = () => {
  wowzaWebRTCPlay.stop();
}

const update = (settings) => {
  state.settings = settings;
  let sendSettings = {};
  for (let key in settings)
  {
    let sendKey = key.substring(statePrefix.length);
    sendKey = sendKey[0].toLowerCase() + sendKey.slice(1);
    sendSettings[sendKey] = settings[key];
  }
  return wowzaWebRTCPlay.set(sendSettings);
}

const onPlayPeerConnected = () => {
  state.playing = true;
  hideErrorPanel();
  $('#player-video').show();
  $("#play-video-container").css("display","none");
  setTimeout(() => {
    $('#player-video').get(0).play();
    $('.plyr').show();
  }, "2000");
}

const onPlayPeerConnectionStopped = () => {
  state.playing = false;
  $('#player-video').hide();
  $("#play-video-container").css("display","flex");
}

// error Handler
const errorHandler = (error) => {
  let message;
  if ( error.message ) {
    message = error.message;
  }
  else {
    message = error
  }
  showErrorPanel(message);
};

const showErrorPanel = (message) => {
  $("#player-btn").hide();
  $("#error-panel").show();
  $('.plyr').hide();
  $("#play-video-container").css("display","flex");
  setTimeout(() => {
    $('#player-btn').html('<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#ffffff; width: 72px; height: 72px; "><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c-7.6 4.2-12.3 12.3-12.3 20.9V344c0 8.7 4.7 16.7 12.3 20.9s16.8 4.1 24.3-.5l144-88c7.1-4.4 11.5-12.1 11.5-20.5s-4.4-16.1-11.5-20.5l-144-88c-7.4-4.5-16.7-4.7-24.3-.5z"/></svg>');
    $("#error-panel").hide();
    $("#player-btn").show();
  }, "5000");
}

const hideErrorPanel = () => {
  $("#error-panel").hide();
  $("#player-btn").show();
  $('.plyr').hide();
  $("#play-video-container").css("display","flex");
}

const initListeners = () => {

  $("#player-btn").click((e) => {
    if (state.playing)
    {
      wowzaWebRTCPlay.stop();
    }
    else
    {
      start(config_stm);
      $('#player-btn').html('<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#c3c6d1; width: 56px; height: 56px; "><path d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/></svg>');      
    }
  });
}

const initFormAndSettings = () => {
  $("#player-video").hide();
  $("#play-video-container").css("display","flex");
}
initFormAndSettings();
init(errorHandler,onPlayPeerConnected,onPlayPeerConnectionStopped);
