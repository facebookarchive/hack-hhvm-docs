<?hh
function render_in_a_box(XHPChild $msg): :xhp {
  return <ui:box>{$msg}</ui:box>;
}
