import WebFont from 'webfontloader';

export default function fontLoader() {
  WebFont.load({
    google: {
      families: ['Roboto:400,700'],
    },
  });
}
