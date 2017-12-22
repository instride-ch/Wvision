(function PageLoader() {
  window.onload = () => {
    const loader = UIkit.util.$('#page-loader');

    UIkit.util.transition(loader, { opacity: 0 });
    UIkit.util.once(loader, 'transitionend', () => UIkit.util.remove(loader));
  };
}(UIkit));
