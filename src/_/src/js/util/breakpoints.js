let breakpoints = {
  current_breakpoint: function() {
    var breakpoint = (window.getComputedStyle($('#breakpoint-detector'), ':before').getPropertyValue('content'));
    return breakpoint.replace(/'/g, "").replace(/"/g, ""); // Remove quotes returned by breakpoint query
  },
}

export default breakpoints;
