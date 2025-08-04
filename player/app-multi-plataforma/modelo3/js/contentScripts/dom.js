! function() {
	document.addEventListener('CONTENT_SCRIPT_STORY_INCOMING_EVENT', function (e) {
		var action = e.detail.action;

		if(action === 'start-story') {
			var videos = document.querySelectorAll('video');
			if(videos.length) {
				var profilePicture = document.querySelector("a[role=link][tabindex='0'][href*='https://www.facebook']>img")
				var username = profilePicture.alt;
				var videoUrl = null;
				for (var i=videos.length - 1;i >= 0;i--) {
					if(videos[i].offsetHeight === 0)
						continue;
					var reactKey = '';
					var keys = Object.keys(videos[i]);
					for (var j=0;j<keys.length;j++) {
						if (keys[j].indexOf ('__reactFiber') != -1) {
							reactKey = keys[j].split('__reactFiber')[1];
							break;
						}
					}
					try {
						videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children[0].props.children.props.implementations[1].data.hdSrc;
					} catch (e){ }
					if (videoUrl == null){
						try {
							videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children[0].props.children.props.implementations[1].data.sdSrc;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children.props.children.props.implementations[1].data.hdSrc;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children.props.children.props.implementations[1].data.sdSrc;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i]['__reactFiber'+reactKey].return.stateNode.props.videoData.$1.hd_src;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i]['__reactFiber'+reactKey].return.stateNode.props.videoData.$1.sd_src;
						} catch (e){ }
					}
					if (videoUrl != null) {
						var storyId = videos[i].parentElement.closest('div[data-id]').dataset.id;
						break;
					}
				}

				if(videoUrl) {
					document.dispatchEvent(new CustomEvent('CONTENT_SCRIPT_STORY_OUTGOING_EVENT', { detail: {action: 'finish-story', data: {video_id: storyId, title: username, type: 'video', url: videoUrl} } }));
				}
			} else {
				/*
				// this code is actually working
				// story is an image
				// select concerned image
				// get content url
				// send it back
				var images = document.querySelectorAll("img[draggable=false]");

				if(images.length){
					// x.parentElement.closest('div[data-id]').dataset.id
					var storyId = images[0].parentElement.closest('div[data-id]').dataset.id;
					var profilePicture = document.querySelector ("a[role=link][tabindex='0'][href*='https://www.facebook']>img")
					var username = profilePicture.alt;
					var imageUrl = images[images.length-1].src;
					
					if(imageUrl) {
						document.dispatchEvent(new CustomEvent('CONTENT_SCRIPT_STORY_OUTGOING_EVENT', { detail: {action: 'finish-story', data: {video_id: storyId, title: username, type: 'image', url: imageUrl} } }));
					}
				}
				*/
			}
		}
	});
	
	document.addEventListener('CONTENT_SCRIPT_REEL_INCOMING_EVENT', function (e) {
		var action = e.detail.action;

		if(action === 'start-reel') {
			var videos = document.querySelectorAll('video');
			if(videos.length) {
				var username = document.querySelector('h2 span.om3e55n1 a').innerText;
				var title = document.querySelector('div.g4qalytl>span.mqmf5637').innerText;
				var videoUrl = null;
				for (var i=videos.length - 1;i >= 0;i--) {
					if(videos[i].offsetHeight === 0)
						continue;
					var reactKey = '';
					var keys = Object.keys(videos[i]);
					for (var j=0;j<keys.length;j++) {
						if (keys[j].indexOf ('__reactFiber') != -1) {
							reactKey = keys[j].split('__reactFiber')[1];
							break;
						}
					}
					try {
						videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children[0].props.children.props.implementations[1].data.hdSrc;
					} catch (e){ }
					if (videoUrl == null){
						try {
							videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children[0].props.children.props.implementations[1].data.sdSrc;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children.props.children.props.implementations[1].data.hdSrc;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i].parentElement.parentElement.parentElement.parentElement['__reactProps'+reactKey].children.props.children.props.implementations[1].data.sdSrc;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i]['__reactFiber'+reactKey].return.stateNode.props.videoData.$1.hd_src;
						} catch (e){ }
					}
					if (videoUrl == null){
						try {
							videoUrl = videos[i]['__reactFiber'+reactKey].return.stateNode.props.videoData.$1.sd_src;
						} catch (e){ }
					}
					if (videoUrl != null) {
						var reelId = videos[i].parentElement.closest('div[data-video-id]').dataset.videoId;
						break;
					}
				}

				if(videoUrl) {
					document.dispatchEvent(new CustomEvent('CONTENT_SCRIPT_REEL_OUTGOING_EVENT', { detail: {action: 'finish-story', data: {video_id: reelId, title: (username + ' - ' + title), type: 'video', url: videoUrl} } }));
				}
			}
		}
	});
	
}();