(function() {
  'use strict';

  const Util = NS.Util;

  const Main = {
    preventSubmitByEnterKey() {
      const formList = document.querySelectorAll('form:not([data-enable-enter="true"])');

      formList.forEach(form => {
        const button = Util.createElement('button', { type: 'submit', disabled: true, style: 'display: none', textContent: 'Prevent-Submit-by-Enter-key' });
        form.prepend(button);
      });
    },
    dataPatternRef() {
      const inputList = document.querySelectorAll('input[data-pattern-ref]');

      inputList.forEach(input => {
        const refId = input.getAttribute('data-pattern-ref');
        const refInput = document.getElementById(refId);
        if (refInput == null) return;

        Util.addEvent(refInput, 'input', () => {
          input.setAttribute('pattern', Util.RegExp.escape(refInput.value));
        });

        Util.triggerEvent(refInput, 'input');
      });
    },
    validateMessage() {
      const inputList = document.querySelectorAll('input[pattern]');

      inputList.forEach(input => {
        const validityState = input.validity;
        const di = input.closest('.mod-FieldDi');
        if (di == null) return;
        const errorBlock = di.querySelector('dd.errorBlock');
        let errorMessage = null;
        const pattern = input.pattern;

        function setMessage(message) {
          input.setCustomValidity(message);
          errorMessage = message;
        }

        function hideMessage() {
          errorBlock.innerHTML = '';
        }

        function showMessage() {
          if (Util.empty(errorMessage)) return;
          Util.createElement('p', { textContent: errorMessage }, errorBlock);
        }

        Util.addEvent(input, 'input', () => {
          if (validityState.patternMismatch) {
            if (pattern === Util.dataJson('ext.regex_mailaddress')) {
              setMessage('「ローカルパート（@の左側）」「@」「ドメイン名」が必要です。');
            }
            if (pattern === '.{8,}') {
              setMessage('このフィールドは、8文字以上でなければなりません。');
            }
            if (input.getAttribute('data-pattern-ref')) {
              const refLabel = input.getAttribute('data-pattern-ref-label') ?? input.getAttribute('data-pattern-ref');
              setMessage(`${refLabel}フィールドと値が一致していません。`);
            }
          }
          else {
            setMessage('');
          }
        });

        Util.addEvent(input, 'focus', () => {
          hideMessage();
        });
        Util.addEvent(input, 'blur', () => {
          showMessage();
        });
      });
    }
  };

  Util.addEvent(document, 'DOMContentLoaded', () => {
    Util.execObjectRoutine(Main);
  });
}());
