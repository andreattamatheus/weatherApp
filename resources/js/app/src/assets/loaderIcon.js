export default function Loader(Component, customStyle) {
  return {
    render(createElement) {
      return createElement(Component, {
        style: customStyle,
      });
    },
  };
}
