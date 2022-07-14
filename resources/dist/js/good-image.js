import { encode, decode } from 'blurhash';

document.addEventListener('alpine:init', () => {
    Alpine.data('goodImage', (config) => ({
        thumbnailLink: config.thumbnailLink,
        link: config.link,
        element: config.element,
        isBackground: config.isBackground,
        forceDisplay: config.forceDisplay,

        loaded: false,
        visible: false,
        displayed: false,
        isReadyToShowBackground: false,

        init() {
            this.$nextTick(() => {
                this.generateBlurImage(this.thumbnailLink, this.element);
            });
        },

        generateBlurImage: async function (thumbnailLink, appendElement) {
            const loadImage = async (src) => {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.onload = () => resolve(img);
                    img.onerror = (...args) => reject(args);
                    img.crossOrigin = 'Anonymous';
                    img.src = src;
                });
            };

            const getImageData = (image) => {
                const canvas = document.createElement('canvas');
                canvas.width = image.width;
                canvas.height = image.height;
                const context = canvas.getContext('2d');
                context.drawImage(image, 0, 0);
                return context.getImageData(
                    0,
                    0,
                    image.width,
                    image.height
                );
            };

            const encodeImageToBlurhash = async (imageUrl) => {
                const image = await loadImage(imageUrl);

                const imageData = getImageData(image);

                const componentX = 4;
                const componentY = 3;

                return encode(
                    imageData.data,
                    imageData.width,
                    imageData.height,
                    componentX,
                    componentY
                );
            };

            const hash = await encodeImageToBlurhash(thumbnailLink);

            const blurWidth = 32;
            const blurHeight = 32;

            const pixels = decode(hash, blurWidth, blurHeight);

            const imageData = new ImageData(pixels, blurWidth, blurHeight);

            const context = appendElement.firstElementChild.getContext('2d');

            context.putImageData(imageData, 0, 0);
        },

        isReadyToShowImage: function () {
            const backgroundOnly = () => {
                this.updateBackground();
                this.isReadyToShowBackground = true;

                return false;
            };

            if (this.displayed) {
                if (this.isBackground) return backgroundOnly();

                return true;
            }

            if (this.loaded && this.visible) {
                if (this.isBackground) return backgroundOnly();

                return (this.displayed = true);
            }

            return false;
        },

        updateBackground: function () {
            this.element.style.backgroundImage = 'url("' + this.link + '")';
        },
    }));
});
