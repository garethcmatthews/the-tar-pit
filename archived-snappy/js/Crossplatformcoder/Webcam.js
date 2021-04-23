var CPC = CPC || {};
CPC.Snappy = function () {

    /**
     * Mozilla Media Constraints
     * @type Object
     */
    var constraintsMozilla = {
        audio: false,
        video: {
            width: {min: 320, ideal: 1920, max: 3840},
            height: {min: 180, ideal: 1080, max: 2160}
        }
    };

    /**
     * Other Browser Media Constraints
     * @type Object
     */

    var constraintsOther = {
        audio: false,
        video: {
            optional: [
                {minWidth: 320},
                {minWidth: 640},
                {minWidth: 800},
                {minWidth: 960},
                {minWidth: 1024},
                {minWidth: 1280},
                {minWidth: 1600},
                {minWidth: 1920},
                {minWidth: 2560}
            ]}
    };


    /**
     * Video Element to Render Video Stream
     * @type String|element
     */
    var videoElement = 'video';

    /**
     * Set Camera Element
     * 
     * @param {string} element
     */
    this.setVideoElement = function (element) {
        if (typeof element === 'string') {
            videoElement = element;
        }
    };

 
    /**
     * ERROR : No getUserMedia Support
     * 
     * Alert the user to this
     */
    function errorBrowserDoesNotSupportGetUserMedia() {
        alert('bad voodo');

    }

    /**
     * ERROR : getUserMedia Failed
     * 
     * @param {string} error
     */
    function errorGetUserMedia(error) {
        alert('Could not access the webcam. It may be in use by another application.');
    }

    /**
     * Main
     * 
     * Run the Snappy application
     */
    this.run = function () {
        navigator.getMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.getUserMedia || null;
        if (navigator.getMedia) {

            navigator.getMedia((navigator.getMedia.name === 'mozGetUserMedia') ? constraintsMozilla : constraintsOther, function (stream) {

                // Event Listeners
                stream.oninactive = function () {
                    errorGetUserMedia();
                };

                var video = document.querySelector('video');
                video.src = window.URL.createObjectURL(stream);
                video.play();

                // Event Listeners
                video.addEventListener("playing", function () {
                    //console.log(video);
                    alert(video.videoWidth + ' : ' + video.videoHeight);
                });

            }, function (error) {
                errorGetUserMedia(error);
            });
        } else {
            errorBrowserDoesNotSupportGetUserMedia();
        }
    };
};
