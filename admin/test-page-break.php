<!DOCTYPE HTML>
<html>
  <head>
    <title>html2pdf Test - Pagebreaks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    

    <style type="text/css">
      /* Avoid unexpected sizing on all elements. */
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      /* CSS styling for before/after/avoid. */
      .before {
        page-break-before: always;
      }
      .after {
        page-break-after: always;
      }
      .avoid {
        page-break-inside: avoid;
      }

      /* Big and bigger elements. */
      .big {
        height: 10.9in;
        background-color: yellow;
        border: 1px solid black;
      }
      .fullpage {
        height: 11in;
        background-color: fuchsia;
        border: 1px solid black;
      }
      .bigger {
        height: 11.1in;
        background-color: aqua;
        border: 1px solid black;
      }

      /* Table styling */
      table {
        border-collapse: collapse;
      }
      td {
        border: 1px solid black;
      }
    </style>

  </head>

  <body>
    <!-- Different options. -->
    <select id="mode">
      <option value="avoid-all">Avoid-all</option>
      <option value="css">CSS</option>
      <option value="legacy">Legacy</option>
      <option value="specify">Specified elements (using before/after/avoid)</option>
    </select>

    <!-- Button to generate PDF. -->
    <button onclick="test()">Generate PDF</button>

    <!-- Div to capture. -->
    <div id="root">
      <p>First line</p>
      <p class="before">Break before</p>
      <p class="after">Break after</p>
      <p>No effect (should be top of 3rd page, using css or specify).</p>
      <p class="html2pdf__page-break">Legacy (should create a break after).</p>
      <p>No effect (should be top of 2nd page, using legacy).</p>
      <p class="avoid big">Big element (should start on new page, using avoid-all/css/specify).</p>
      <p>No effect (should start on next page *only* using avoid-all).</p>
      <p>No effect (for spacing).</p>
      <p class="avoid fullpage">Full-page element (should start on new page using avoid-all/css/specify).</p>
      <p>No effect (for spacing).</p>
      <p class="avoid bigger">Even bigger element (should continue normally, because it's more than a page).</p>

      <!-- Advanced avoid-all tests. -->
      <div>
        <p>No effect inside parent div (testing avoid-all - no break yet because parent is more than a page).</p>
        <p class="big">Big element inside parent div (testing avoid-all - should have break before this).</p>
      </div>
      <table>
        <tr>
          <td>Cell 1-1 - start of new page (avoid-all only)</td>
          <td>Cell 1-2 - start of new page (avoid-all only)</td>
        </tr>
        <tr class="big">
          <td>Cell 2-1 - start of another new page (avoid-all only)</td>
          <td>Cell 2-2 - start of another new page (avoid-all only)</td>
        </tr>
      </table>
    </div>

    <!-- Include html2pdf bundle. -->
    <script src="../assets/js/html2pdf.bundle.js"></script>

    <script>
      // Pagebreak fields:  mode, before, after, avoid
      // Pagebreak modes:   'avoid-all', 'css', 'legacy'

      function test() {
        // Get the element.
        var element = document.getElementById('root');
    
        // Choose pagebreak options based on mode.
        var mode = document.getElementById('mode').value;
        var pagebreak = (mode === 'specify') ?
            { mode: '', before: '.before', after: '.after', avoid: '.avoid' } :
            { mode };

        // Generate the PDF.
        html2pdf().from(element).set({
          filename: mode + '.pdf',
          pagebreak: pagebreak,
          jsPDF: {orientation: 'portrait', unit: 'in', format: 'letter', compressPDF: true}
        }).save();
      }
    </script>
  </body>
</html>