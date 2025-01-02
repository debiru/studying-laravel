(function() {
  'use strict';

  const NS = {
    _app: {
      dataJson: JSON.parse(document.querySelector('meta[name="data-json"]').content),
    },
  };
  window.NS = NS;

  const Util = {
    get(obj, keys, defaultValue = null) {
      if (!Array.isArray(keys)) keys = keys.split('.');
      let target = obj;
      for (const key of keys) {
        if (target?.[key] === undefined) return defaultValue;
        target = target[key];
      }
      return target;
    },
    dataJson(keys, defaultValue = null) {
      return Util.get(NS._app.dataJson, keys, defaultValue);
    },
    fetchResult(fetchObj) {
      return fetchObj.then(response => response.headers.get('Content-Type').startsWith('application/json') ? response.json() : response);
    },
    fetch(url) {
      return Util.fetchResult(fetch(url));
    },
    formData(obj) {
      if (!(obj instanceof Object)) obj = {};
      const formData = new FormData();
      Util.each(obj, (key, value) => formData.append(key, value));
      return formData;
    },
    fetchPost(url, obj) {
      return Util.fetchResult(fetch(url, {method: 'POST', body: Util.formData(obj)}));
    },
    formPost(url, obj) {
      const form = document.createElement('form');
      form.action = url;
      form.method = 'post';
      Util.addEvent(form, 'formdata', evt => {
        Util.each(obj, (key, value) => evt.formData.append(key, value));
      });
      document.body.append(form);
      form.submit();
    },
    execObjectRoutine(obj) {
      for (const key of Object.keys(obj)) {
        if (typeof obj[key] === 'function') {
          const retval = obj[key]();
          if (retval != null) return retval;
        }
      }
    },
    random(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min + 1) + min);
    },
    arrayRandom(array) {
      return array[Util.random(0, array.length - 1)];
    },
    arrayColumn(iter, retrieveKey) {
      const result = {};
      Util.each(iter, (key, value) => {
        result[value[retrieveKey]] = value;
      });
      return result;
    },
    arrayGroupBy(iter, retrieveKey) {
      const result = {};
      Util.each(iter, (key, value) => {
        result[value[retrieveKey]] ??= [];
        result[value[retrieveKey]].push(value);
      });
      return result;
    },
    each(arg, callback) {
      if (arg.forEach) {
        arg.forEach((value, idx) => callback.call(arg, idx, value));
      }
      else {
        Object.keys(arg).forEach(key => callback.call(arg, key, arg[key]));
      }
    },
    objMap(obj, callback) {
      const ret = {};
      Util.each(obj, (key, value) => ret[key] = callback(value, key));
      return ret;
    },
    empty(arg) {
      let isEmpty = arg == null || arg === false || arg === '';
      if (!isEmpty) {
        if (Array.isArray(arg) && arg.length === 0) isEmpty = true;
        if (Object.getPrototypeOf(arg).constructor.name === 'Object' && Object.keys(arg).length === 0) isEmpty = true;
      }
      return isEmpty;
    },
    delegateEvent(selector, type, listener, options) {
      if (options == null) options = false;
      document.addEventListener(type, evt => {
        for (let elem = evt.target; elem && elem !== document; elem = elem.parentNode) {
          if (elem.matches(selector)) return listener.call(elem, evt);
        }
      }, options);
    },
    addEvent(elems, type, listener, options) {
      if (Util.empty(elems)) return null;
      if (!elems.forEach) elems = [elems];
      if (options == null) options = false;
      elems.forEach((elem, idx) => elem.addEventListener(type, evt => { listener.call(elem, evt, idx); }, options));
    },
    triggerEvent(elems, type, options) {
      if (Util.empty(elems)) return null;
      if (!elems.forEach) elems = [elems];
      const event = new Event(type, options);
      elems.forEach(elem => elem.dispatchEvent(event));
    },
    elemsFilter(elems, selector, single = false) {
      const result = [];
      for (const elem of elems) {
        if (elem.matches(selector)) result.push(elem);
      }
      return single ? result[0] : result;
    },
    createElement(name, attrs, parent) {
      if (attrs == null) attrs = {};
      const elem = document.createElement(name);
      const { textContent, ...restAttrs } = attrs;
      if (textContent != null) elem.textContent = textContent;
      for (const [key, value] of Object.entries(restAttrs)) {
        elem.setAttribute(key, value);
      }
      if (parent != null) parent.append(elem);
      return elem;
    },
    debounce(func, interval = 50) {
      return function(...args) {
        clearTimeout(func._debounceTid);
        func._debounceTid = setTimeout(() => {
          delete func._debounceTid;
          func.call(this, ...args);
        }, interval);
      };
    },
    sprintf(format, ...args) {
      let p = 0;
      return format.replace(/%./g, function(m) {
        if (m === '%%') return '%';
        if (m === '%s') return args[p++];
        return m;
      });
    },
    ucFirst(str) {
      return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    },
    clamp(min, value, max, defaultValue = null) {
      if (value < min) return defaultValue ?? min;
      if (max < value) return defaultValue ?? max;
      return value;
    },
    range(start, stop, step = 1) {
      return Array.from({ length: (stop - start) / step + 1 }, (_, i) => start + i * step);
    },
    getOrdinalWithSuffix(n) {
      const mod10 = n % 10, mod100 = n % 100;
      if (mod10 === 1 && mod100 !== 11) return n + 'st';
      if (mod10 === 2 && mod100 !== 12) return n + 'nd';
      if (mod10 === 3 && mod100 !== 13) return n + 'rd';
      return n + 'th';
    },
    getNumberFromOrdinal(ordinal) {
      return ordinal.replace(/(?:st|nd|rd|th)$/, '') | 0;
    },
    RegExp: {
      escape(str) {
        return str.replace(/[\\^$.*+?()[\]{}|]/g, '\\$&');
      },
    },
    dateTime: {
      methodMap: {year: 'getFullYear', month: 'getMonth', day: 'getDate', hour: 'getHours', minute: 'getMinutes', second: 'getSeconds'},
      normalizeMap(map = null, adderMap = null) {
        // map = {year: 2000, month: 1, day: 1, hour: 12, minute: 34, second: 56}
        // map = {year: '2000', month: '01', day: '01', hour: '23', minute: '45', second: '67'}
        map = Object.assign({}, Util.dateTime.dateObjToMap(new Date()), map);
        adderMap ??= {};
        map = Util.objMap(map, (value, key) => Number(value) + (adderMap[key] ?? 0));

        const dateObj = new Date(map.year, map.month - 1, map.day, map.hour, map.minute, map.second);
        return Util.dateTime.dateObjToMap(dateObj);
      },
      dateObjToMap(dateObj) {
        const map = Util.objMap(Util.dateTime.methodMap, method => dateObj[method]());
        ++map.month;
        return map;
      },
      strToMap(str) {
        // str = '2000-01-01 12:34:56' or '2000-1-1 1:2:3' or '2000-01-01T12:34:56' or '2000/01/01 12:34:56'
        const map = str.match(/^(?<year>\d+)\D+(?<month>\d+)\D+(?<day>\d+)\D+(?<hour>\d+)\D+(?<minute>\d+)\D+(?<second>\d+)/)?.groups;
        return map;
      },
      zeroPaddingMap(map) {
        return Util.objMap(map, (value, key) => String(value).padStart(key === 'year' ? 4 : 2, '0'));
      },
    },
  };
  NS.Util = Util;

  // Util.addEvent(document, 'DOMContentLoaded', () => {
  //   Util.execObjectRoutine(Main);
  // });

  Util.addEvent(window, 'unload', () => {});
}());
