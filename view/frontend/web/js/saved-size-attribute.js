define([], function () {
    if (! ('Likemusic_SaveSize' in window)) {
        return null;
    }

    var moduleConfig = window.Likemusic_SaveSize;

    if (! ('savedSizeAttribute' in moduleConfig)) {
        return null;
    }

    return moduleConfig.savedSizeAttribute;
});
