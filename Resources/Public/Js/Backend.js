/**
 * 2023/07/19
 * Author: Steffen Kroggel <developer@steffenkroggel.de>
 * Verion: 9.5.4
 */

/**
 * Fix for Google Chrome
 * see: https://stackoverflow.com/questions/43233115/chrome-content-scripts-arent-working-domcontentloaded-listener-does-not-execut
 */
if(document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded',txBeDefenderAfterDOMLoaded);
} else {
  txBeDefenderAfterDOMLoaded();
}

function txBeDefenderAfterDOMLoaded() {
  const button = document.getElementById('tx-bedefender-code-button');
  const usernameField = document.getElementById('t3-username');

  if (button !== null) {
    button.addEventListener("click", function (e) {

      e.preventDefault();
      if (button.hasAttribute('data-disabled')) {
        return false;
      }

      // check for username
      if (usernameField.value == '') {
        alert(txBedefenderTranslations.errorNoUserName);
        return false;
      }

      // disable visually and technically
      button.classList.add('disabled');
      button.setAttribute('data-disabled', '1');

      // do request
      var ajaxRequest = new XMLHttpRequest();
      ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState == 1) {
          console.log("Established server connection.");
        } else if (ajaxRequest.readyState == 2) {
          console.log("Request received by server.");
        } else if (ajaxRequest.readyState == 3) {
          console.log("Processing request.");
        } else if (ajaxRequest.readyState == 4) {
          console.log("Done loading!");
          try {
            let jsonResponse = JSON.parse(ajaxRequest.responseText);
            let status = 500;
            if (jsonResponse !== null) {
              status = jsonResponse.status;
            }
            if ((status == 200) || (status == 400)) {
              alert(txBedefenderTranslations.requestSuccessMessage);
            } else {
              alert (txBedefenderTranslations.requestErrorMessage);
            }
          } catch (error) {
            alert (txBedefenderTranslations.requestErrorMessage);
            console.error(error);
          }

          // enable visually and technically
          button.classList.remove('disabled');
          button.removeAttribute('data-disabled');

        } else {
          console.log("Something went wrong. :(");
          alert (txBedefenderTranslations.requestErrorMessage);

          // enable visually and technically
          button.classList.remove('disabled');
          button.removeAttribute('data-disabled');
        }
      }

      // build url
      let url = '/index.php?type=1689670530&tx_bedefender_authcode[username]=' + usernameField.value;

      ajaxRequest.open("GET", url, true);
      ajaxRequest.send();

    });
  }
}
