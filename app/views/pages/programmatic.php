<style>
body {
   background-color: #f5f5f5;
}

.blocklyTreeRoot {
   color: #333;
}
#facet_editor {
   display: none;
   font-size: 2em;
}
.scriptureList {
   margin: 5rem auto;
}
.scriptureList .facet_title {
   cursor: pointer;
   display: inline-block;
}
.scriptureList .contents {
   text-align: left;
   height: 0;
   max-height: 250px;
   overflow-x: hidden;
   overflow-y: hidden;
   display: block;
   clear: both;
   -webkit-transition: all 1s linear;
   transition: all 1s linear;
}
.scriptureList .contents h3 {
   margin: 1rem 0;
}
.scriptureList li {
   background-color: #3c67c8;
   color: #f0f0f0;
}
.scriptureList li a {
   color: #040627;
}
.scriptureList li a:hover {
   color: #fff;
}

.nightMode .scriptureList li a {
   color: #7b8cd2;
}
.nightMode .scriptureList li a:hover {
   color: #33cdff;
}
.scriptureList li,
.nightMode .scriptureList li {
   min-height: 75px;
   padding-left: 30px;
   padding: 0 15px 1rem;
   border: 1px solid #a1abce;
}
.scriptureList li.selected,
.nightMode .scriptureList li.selected {
   background-color: #382FC2;
   color: #fff;
   border-color: #041277;
}
.scriptureList .relative_wrapper {
   position: relative;
}
.scriptureList .action_menu_left,
.scriptureList .action_menu_right {
   font-size: 1.2em;
   top: 20px;
}

.scriptureList .action_menu_left {
   position: absolute;
   left: 0;
}
.scriptureList .action_menu_right {
   position: absolute;
   right: 0;
}
.scriptureList .action_menu a {
   padding-top: 20px;
   width: 25px;
   height: 50px;
   display: block;
   float: left;
}
.scriptureList .action_menu_right .drag_btn {
   cursor: all-scroll;
}
.scriptureList li.sortable-chosen,
.nightMode .scriptureList li.sortable-chosen {
   background-color: #382FC2;
   color: #fff;
   /* border-color: #4D3D6C; */
   border-color: #5041FF;
}
.nightMode .scriptureList li.open {
   background-color: #222830;
   color: #ededed;
}
.nightMode .scriptureList li.open a {
   color: #ededed;
}
.nightMode .scriptureList li.open a:hover {
   color: #ededed;
}

.nightMode #facet_editor {
   color: #eee;
   background-color: #111;
}
.nightMode .scriptureList li {
   background-color: #2c354a;
   border-color: #1D2739;
}
.scriptureList li .remove_btn {
   display: none;
}
.scriptureList li.open .remove_btn {
   display: block;
}
.scriptureList li.open .contents {
   height: auto;
   overflow-y: auto;
   margin-top: 1rem;
}
.scriptureList li.open .contents .result .verse {
   font-size: 1.2em;
}


.farSide {
  text-align: right;
}

html[dir="RTL"] .farSide {
  text-align: left;
}

/* Buttons */
button {
  margin: 5px;
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ddd;
  font-size: large;
  background-color: #eee;
  color: #000;
}
button.primary {
  /*
  border: 1px solid #dd4b39;
  background-color: #dd4b39;
  */
  color: #fff;
}
button.primary>img {
  opacity: 1;
}
button>img {
  opacity: 0.6;
  vertical-align: text-bottom;
}
button:hover>img {
  opacity: 1;
}
button:active {
  border: 1px solid #888 !important;
}
button:hover {
  box-shadow: 2px 2px 5px #888;
}
button.disabled:hover>img {
  opacity: 0.6;
}
button.disabled {
  display: none;
}
button.notext {
  font-size: 10%;
}

h1 {
  font-weight: normal;
  font-size: 140%;
  margin-left: 5px;
  margin-right: 5px;
}

/* Tabs */
#tabRow>td {
  border: 1px solid #ccc;
  border-bottom: none;
}
td.tabon {
  border-bottom-color: #ddd !important;
  background-color: #4268d9;
  padding: 5px 19px;
}
td.taboff {
  cursor: pointer;
  padding: 5px 19px;
}
td.taboff:hover {
  background-color: #94799b;
}
td.tabmin {
  border-top-style: none !important;
  border-left-style: none !important;
  border-right-style: none !important;
}
td.tabmax {
  border-top-style: none !important;
  border-left-style: none !important;
  border-right-style: none !important;
  width: 99%;
  padding-left: 10px;
  padding-right: 10px;
  text-align: right;
}
html[dir=rtl] td.tabmax {
  text-align: left;
}

table {
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  border: none;
}
td {
  padding: 0;
  vertical-align: top;
}
.content {
  visibility: hidden;
  margin: 0;
  padding: 1ex;
  position: absolute;
  direction: ltr;
}
pre.content,
textarea.content {
  border: 1px solid #ccc;
  overflow: scroll;
  background-color: #fff;
}
#content_blocks {
   padding: 0;
}
.blocklySvg {
  border-top: none !important;
}
#content_xml {
  resize: none;
  outline: none;
  border: 1px solid #ccc;
  font-family: monospace;
  overflow: scroll;
}
#languageMenu {
  vertical-align: top;
  margin-left: 15px;
  margin-right: 15px;
  margin-top: 15px;
}

/* Buttons 
button {
  padding: 1px 10px;
  margin: 1px 5px;
}
*/
/* Sprited icons. */
.icon21 {
  height: 21px;
  width: 21px;
  background-image: url(icons.png);
}
.trash {
  background-position: 0px 0px;
}
.link {
  background-position: -21px 0px;
}
.run {
  background-position: -42px 0px;
}


</style>
<?php
$bible = Bible::getInstance();
render('pages/programmatic/header.php', array('search_string' => $search_string));
?>


<div class="page">

   <input id="facet_editor" type="text" value="<?= (!empty($search_string)) ? $search_string : ''; ?>" placeholder="Search" />


   <? if (!empty($search_results)) 
   {
      ?>
      <div class="row">
         <div class="col s12 m3">
            <div class="hide">
               <select id="languageMenu"></select>
            </div>
            <div class="left">
               <span id="title">...</span>
            </div>
         </div>
         <div class="col s12 m6 center-align" style="margin-bottom: 1rem;">
            &quot;<?= $bible->friendlyReference($search_string) ?>&quot;
         </div>
         <div class="col s12 m3 center-align">
            <i class="large material-icons">android</i><br/>
            <div class="center-align">
               <a id="trashButton" class="waves-effect waves-dark btn grey darken-1"><i class="fas fa-trash"></i></a>
               <a id="linkButton" class="waves-effect waves-dark btn indigo lighten-1"><i class="fas fa-link"></i></a>
               <a id="runButton" class="waves-effect waves-dark btn pink accent-4"><i class="fas fa-play"></i></a>
            </div>
         </div>
      </div>


      <script>
         if ('BlocklyStorage' in window) {
            BlocklyStorage.HTTPREQUEST_ERROR = 'There was a problem with the request.\n';
            BlocklyStorage.LINK_ALERT = 'Share your blocks with this link:\n\n%1';
            BlocklyStorage.HASH_ERROR = 'Sorry, "%1" doesn\'t correspond with any saved Blockly file.';
            BlocklyStorage.XML_ERROR = 'Could not load your saved file.\n'+
               'Perhaps it was created with a different version of Blockly?';
         } else {
            document.write('<p id="sorry">Sorry, cloud storage is not available.  This demo must be hosted on App Engine.</p>');
         }
      </script>

      <table height="80%">
         <tr>
            <td colspan=2>
            <table>
               <tr id="tabRow" height="1em">
                  <td id="tab_blocks" class="tabon">...</td>
                  <td class="tabmin">&nbsp;</td>
                  <td id="tab_javascript" class="taboff">JavaScript</td>
                  <td class="tabmin">&nbsp;</td>
                  <td id="tab_python" class="taboff">Python</td>
                  <td class="tabmin">&nbsp;</td>
                  <td id="tab_php" class="taboff">PHP</td>
                  <td class="tabmin">&nbsp;</td>
                  <td id="tab_lua" class="taboff">Lua</td>
                  <td class="tabmin">&nbsp;</td>
                  <td id="tab_dart" class="taboff">Dart</td>
                  <td class="tabmin">&nbsp;</td>
                  <td id="tab_xml" class="taboff">XML</td>
               </tr>
            </table>
            </td>
         </tr>
         <tr>
            <td height="99%" colspan=2 id="content_area">
            </td>
         </tr>
      </table>
      <div id="content_blocks" class="content"></div>
      <pre id="content_javascript" class="content prettyprint lang-js"></pre>
      <pre id="content_python" class="content prettyprint lang-py"></pre>
      <pre id="content_php" class="content prettyprint lang-php"></pre>
      <pre id="content_lua" class="content prettyprint lang-lua"></pre>
      <pre id="content_dart" class="content prettyprint lang-dart"></pre>
      <textarea id="content_xml" class="content" wrap="off"></textarea>

         <xml xmlns="https://developers.google.com/blockly/xml" id="toolbox" style="display: none">
         <category name="Logic" colour="%{BKY_LOGIC_HUE}">
            <category name="If">
               <block type="controls_if"></block>
               <block type="controls_if">
               <mutation else="1"></mutation>
               </block>
               <block type="controls_if">
               <mutation elseif="1" else="1"></mutation>
               </block>
            </category>
            <category name="Boolean" colour="%{BKY_LOGIC_HUE}">
               <block type="logic_compare"></block>
               <block type="logic_operation"></block>
               <block type="logic_negate"></block>
               <block type="logic_boolean"></block>
               <block type="logic_null"></block>
               <block type="logic_ternary"></block>
            </category>
         </category>
         <category name="Loops" colour="%{BKY_LOOPS_HUE}">
            <block type="controls_repeat_ext">
               <value name="TIMES">
               <block type="math_number">
                  <field name="NUM">10</field>
               </block>
               </value>
            </block>
            <block type="controls_whileUntil"></block>
            <block type="controls_for">
               <field name="VAR">i</field>
               <value name="FROM">
               <block type="math_number">
                  <field name="NUM">1</field>
               </block>
               </value>
               <value name="TO">
               <block type="math_number">
                  <field name="NUM">10</field>
               </block>
               </value>
               <value name="BY">
               <block type="math_number">
                  <field name="NUM">1</field>
               </block>
               </value>
            </block>
            <block type="controls_forEach"></block>
            <block type="controls_flow_statements"></block>
         </category>
         <category name="Math" colour="%{BKY_MATH_HUE}">
            <block type="math_number">
               <field name="NUM">123</field>
            </block>
            <block type="math_arithmetic"></block>
            <block type="math_single"></block>
            <block type="math_trig"></block>
            <block type="math_constant"></block>
            <block type="math_number_property"></block>
            <block type="math_round"></block>
            <block type="math_on_list"></block>
            <block type="math_modulo"></block>
            <block type="math_constrain">
               <value name="LOW">
               <block type="math_number">
                  <field name="NUM">1</field>
               </block>
               </value>
               <value name="HIGH">
               <block type="math_number">
                  <field name="NUM">100</field>
               </block>
               </value>
            </block>
            <block type="math_random_int">
               <value name="FROM">
               <block type="math_number">
                  <field name="NUM">1</field>
               </block>
               </value>
               <value name="TO">
               <block type="math_number">
                  <field name="NUM">100</field>
               </block>
               </value>
            </block>
            <block type="math_random_float"></block>
            <block type="math_atan2"></block>
         </category>
         <category name="Lists" colour="%{BKY_LISTS_HUE}">
            <block type="lists_create_empty"></block>
            <block type="lists_create_with"></block>
            <block type="lists_repeat">
               <value name="NUM">
               <block type="math_number">
                  <field name="NUM">5</field>
               </block>
               </value>
            </block>
            <block type="lists_length"></block>
            <block type="lists_isEmpty"></block>
            <block type="lists_indexOf"></block>
            <block type="lists_getIndex"></block>
            <block type="lists_setIndex"></block>
         </category>
         <sep></sep>
         <category name="Variables" custom="VARIABLE" colour="%{BKY_VARIABLES_HUE}">
         </category>
         <category name="Functions" custom="PROCEDURE" colour="%{BKY_PROCEDURES_HUE}">
         </category>
         <sep></sep>
         <category name="Library" expanded="true">
            <category name="Randomize">
               <block type="procedures_defnoreturn">
               <mutation>
                  <arg name="list"></arg>
               </mutation>
               <field name="NAME">randomize</field>
               <statement name="STACK">
                  <block type="controls_for" inline="true">
                     <field name="VAR">x</field>
                     <value name="FROM">
                     <block type="math_number">
                        <field name="NUM">1</field>
                     </block>
                     </value>
                     <value name="TO">
                     <block type="lists_length" inline="false">
                        <value name="VALUE">
                           <block type="variables_get">
                           <field name="VAR">list</field>
                           </block>
                        </value>
                     </block>
                     </value>
                     <value name="BY">
                     <block type="math_number">
                        <field name="NUM">1</field>
                     </block>
                     </value>
                     <statement name="DO">
                     <block type="variables_set" inline="false">
                        <field name="VAR">y</field>
                        <value name="VALUE">
                           <block type="math_random_int" inline="true">
                           <value name="FROM">
                              <block type="math_number">
                                 <field name="NUM">1</field>
                              </block>
                           </value>
                           <value name="TO">
                              <block type="lists_length" inline="false">
                                 <value name="VALUE">
                                 <block type="variables_get">
                                    <field name="VAR">list</field>
                                 </block>
                                 </value>
                              </block>
                           </value>
                           </block>
                        </value>
                        <next>
                           <block type="variables_set" inline="false">
                           <field name="VAR">temp</field>
                           <value name="VALUE">
                              <block type="lists_getIndex" inline="true">
                                 <mutation statement="false" at="true"></mutation>
                                 <field name="MODE">GET</field>
                                 <field name="WHERE">FROM_START</field>
                                 <value name="AT">
                                 <block type="variables_get">
                                    <field name="VAR">y</field>
                                 </block>
                                 </value>
                                 <value name="VALUE">
                                 <block type="variables_get">
                                    <field name="VAR">list</field>
                                 </block>
                                 </value>
                              </block>
                           </value>
                           <next>
                              <block type="lists_setIndex" inline="false">
                                 <value name="AT">
                                 <block type="variables_get">
                                    <field name="VAR">y</field>
                                 </block>
                                 </value>
                                 <value name="LIST">
                                 <block type="variables_get">
                                    <field name="VAR">list</field>
                                 </block>
                                 </value>
                                 <value name="TO">
                                 <block type="lists_getIndex" inline="true">
                                    <mutation statement="false" at="true"></mutation>
                                    <field name="MODE">GET</field>
                                    <field name="WHERE">FROM_START</field>
                                    <value name="AT">
                                       <block type="variables_get">
                                       <field name="VAR">x</field>
                                       </block>
                                    </value>
                                    <value name="VALUE">
                                       <block type="variables_get">
                                       <field name="VAR">list</field>
                                       </block>
                                    </value>
                                 </block>
                                 </value>
                                 <next>
                                 <block type="lists_setIndex" inline="false">
                                    <value name="AT">
                                       <block type="variables_get">
                                       <field name="VAR">x</field>
                                       </block>
                                    </value>
                                    <value name="LIST">
                                       <block type="variables_get">
                                       <field name="VAR">list</field>
                                       </block>
                                    </value>
                                    <value name="TO">
                                       <block type="variables_get">
                                       <field name="VAR">temp</field>
                                       </block>
                                    </value>
                                 </block>
                                 </next>
                              </block>
                           </next>
                           </block>
                        </next>
                     </block>
                     </statement>
                  </block>
               </statement>
               </block>
            </category>
            <category name="Jabberwocky">
               <block type="text_print">
               <value name="TEXT">
                  <block type="text">
                     <field name="TEXT">'Twas brillig, and the slithy toves</field>
                  </block>
               </value>
               <next>
                  <block type="text_print">
                     <value name="TEXT">
                     <block type="text">
                        <field name="TEXT">  Did gyre and gimble in the wabe:</field>
                     </block>
                     </value>
                     <next>
                     <block type="text_print">
                        <value name="TEXT">
                           <block type="text">
                           <field name="TEXT">All mimsy were the borogroves,</field>
                           </block>
                        </value>
                        <next>
                           <block type="text_print">
                           <value name="TEXT">
                              <block type="text">
                                 <field name="TEXT">  And the mome raths outgrabe.</field>
                              </block>
                           </value>
                           </block>
                        </next>
                     </block>
                     </next>
                  </block>
               </next>
               </block>
               <block type="text_print">
               <value name="TEXT">
                  <block type="text">
                     <field name="TEXT">"Beware the Jabberwock, my son!</field>
                  </block>
               </value>
               <next>
                  <block type="text_print">
                     <value name="TEXT">
                     <block type="text">
                        <field name="TEXT">  The jaws that bite, the claws that catch!</field>
                     </block>
                     </value>
                     <next>
                     <block type="text_print">
                        <value name="TEXT">
                           <block type="text">
                           <field name="TEXT">Beware the Jubjub bird, and shun</field>
                           </block>
                        </value>
                        <next>
                           <block type="text_print">
                           <value name="TEXT">
                              <block type="text">
                                 <field name="TEXT">  The frumious Bandersnatch!"</field>
                              </block>
                           </value>
                           </block>
                        </next>
                     </block>
                     </next>
                  </block>
               </next>
               </block>
            </category>
         </category>
         </xml>


   <? } ?>
   <? /*
   <? if (count($_SESSION['bookmarks'])) { ?>

   <h2>Bookmarks</h2>
   <ul id="bookmarks" class="scriptureList" data-search="">
      <?php
      foreach ($_SESSION['bookmarks'] as $bookmark)
      {
         echo '<li data-id="'.$bookmark.'">'.$bookmark.'</li>';
      }
      ?>
   
   </ul>

   <? } ?>
   */ ?>

</div>

<script>
$(document).ready(function() 
{
   
});
</script>
