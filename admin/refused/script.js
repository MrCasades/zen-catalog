//открыть задание

function viewTask(url) {
  const windowHeight = 400
  const windowWidth = 900
  window.open(url, '', 'scrollbars=1, height=' + Math.min(windowHeight, screen.availHeight) + ',width=' + Math.min(windowWidth, screen.availWidth) + ',left=' + Math.max(0, (screen.availWidth - windowWidth)/2) + ',top=' + Math.max (0, (screen.availHeight - windowHeight)/2));
}