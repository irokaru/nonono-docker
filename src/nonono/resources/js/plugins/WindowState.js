export default {
  install(app) {
    const state = {
      scrollX: 0,
      scrollY: 0,
      width  : 0,
      height : 0,
      mouseX : 0,
      mouseY : 0,
    };

    const onScroll = () => {
      state.scrollX = window.pageXOffset;
      state.scrollY = window.pageYOffset;
    };

    const onResize = () => {
      state.width  = document.documentElement.clientWidth;
      state.height = document.documentElement.clientHeight;
    }

    const mouseMove = (e) => {
      state.mouseX = e.pageX;
      state.mouseY = e.pageY;
    }

    document.addEventListener('scroll', onScroll);

    window.addEventListener('resize',    onResize);
    window.addEventListener('mousemove', mouseMove);
    window.addEventListener('load',      onScroll);

    onResize();

    app.config.globalProperties.$window = state;

    app.$isSmartPhone = function() {
      return state.width <= 700;
    };
  },
};
