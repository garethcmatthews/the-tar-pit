var CPC = CPC || {};

/*
var resolution = new CPC.UserMediaHelper();
$foo = resolution.getVideoResolution();
alert($foo);
*/

var snappy = new CPC.Snappy();
snappy.setVideoElement('video');
snappy.run();

