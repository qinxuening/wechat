/**
 * @author qxn
 */
layui.define(['jquery'], function(exports){
    $ = layui.jquery;
    var $container;
    var listener;
    var toastId = 0;
    var toastType = {
        error: 'error',
        info: 'info',
        success: 'success',
        warning: 'warning'
    };

    // var toastr = {
    //     clear:  obj.clear,
    //     remove:  obj.remove,
    //     error:  obj.error,
    //     getContainer: obj.getContainer,
    //     info:  obj.info,
    //     options: {},
    //     subscribe:  obj.subscribe,
    //     success:  obj.success,
    //     version: '2.1.4',
    //     warning:  obj.warning
    // };

    var previousToast;
    obj = {
         options: {},
         error:function(message, title, optionsOverride) {
            return obj.notify({
                type: toastType.error,
                iconClass:  obj.getOptions().iconClasses.error,
                message: message,
                optionsOverride: optionsOverride,
                title: title
            });
        },

        getContainer: function (options, create) {
            if (!options) { options =  obj.getOptions(); }
            $container = $('#' + options.containerId);
            if ($container.length) {
                return $container;
            }
            if (create) {
                $container =  obj.createContainer(options);
            }
            return $container;
        },

        info:function (message, title, optionsOverride) {
            return  obj.notify({
                type: toastType.info,
                iconClass:  obj.getOptions().iconClasses.info,
                message: message,
                optionsOverride: optionsOverride,
                title: title
            });
        },

        subscribe:function (callback) {
            listener = callback;
        },

        success:function(message, title, optionsOverride) {
            return  obj.notify({
                type: toastType.success,
                iconClass:  obj.getOptions().iconClasses.success,
                message: message,
                optionsOverride: optionsOverride,
                title: title
            });
        },

        warning:function (message, title, optionsOverride) {
            return  obj.notify({
                type: toastType.warning,
                iconClass: obj.getOptions().iconClasses.warning,
                message: message,
                optionsOverride: optionsOverride,
                title: title
            });
        },

        clear:function ($toastElement, clearOptions) {
            var options =  obj.getOptions();
            if (!$container) {  obj.getContainer(options); }
            if (! obj.clearToast($toastElement, options, clearOptions)) {
                obj.clearContainer(options);
            }
        },

        remove:function ($toastElement) {
            var options =  obj.getOptions();
            if (!$container) {  obj.getContainer(options); }
            if ($toastElement && $(':focus', $toastElement).length === 0) {
                obj.removeToast($toastElement);
                return;
            }
            if ($container.children().length) {
                $container.remove();
            }
        },

        // internal functions

        clearContainer: function  (options) {
            var toastsToClear = $container.children();
            for (var i = toastsToClear.length - 1; i >= 0; i--) {
                obj.clearToast($(toastsToClear[i]), options);
            }
        },

        clearToast: function  ($toastElement, options, clearOptions) {
            var force = clearOptions && clearOptions.force ? clearOptions.force : false;
            if ($toastElement && (force || $(':focus', $toastElement).length === 0)) {
                $toastElement[options.hideMethod]({
                    duration: options.hideDuration,
                    easing: options.hideEasing,
                    complete: function () {  obj.removeToast($toastElement); }
                });
                return true;
            }
            return false;
        },

        createContainer: function (options) {
            $container = $('<div/>')
                .attr('id', options.containerId)
                .addClass(options.positionClass);

            $container.appendTo($(options.target));
            return $container;
        },

        getDefaults:function () {
            return {
                tapToDismiss: true,
                toastClass: 'toast',
                containerId: 'toast-container',
                debug: false,

                showMethod: 'fadeIn', //fadeIn, slideDown, and show are built into jQuery
                showDuration: 300,
                showEasing: 'swing', //swing and linear are built into jQuery
                onShown: undefined,
                hideMethod: 'fadeOut',
                hideDuration: 1000,
                hideEasing: 'swing',
                onHidden: undefined,
                closeMethod: false,
                closeDuration: false,
                closeEasing: false,
                closeOnHover: true,

                extendedTimeOut: 1000,
                iconClasses: {
                    error: 'toast-error',
                    info: 'toast-info',
                    success: 'toast-success',
                    warning: 'toast-warning'
                },
                iconClass: 'toast-info',
                positionClass: 'toast-top-right',
                timeOut: 5000, // Set timeOut and extendedTimeOut to 0 to make it sticky
                titleClass: 'toast-title',
                messageClass: 'toast-message',
                escapeHtml: false,
                target: 'body',
                closeHtml: '<button type="button">&times;</button>',
                closeClass: 'toast-close-button',
                newestOnTop: true,
                preventDuplicates: false,
                progressBar: false,
                progressClass: 'toast-progress',
                rtl: false
            };
        },

        publish:function (args) {
            if (!listener) { return; }
            listener(args);
        },

        notify:function (map) {
            var options =  obj.getOptions();
            var iconClass = map.iconClass || options.iconClass;

            if (typeof (map.optionsOverride) !== 'undefined') {
                options = $.extend(options, map.optionsOverride);
                iconClass = map.optionsOverride.iconClass || iconClass;
            }

            if (shouldExit(options, map)) { return; }

            toastId++;

            $container =  obj.getContainer(options, true);

            var intervalId = null;
            var $toastElement = $('<div/>');
            var $titleElement = $('<div/>');
            var $messageElement = $('<div/>');
            var $progressElement = $('<div/>');
            var $closeElement = $(options.closeHtml);
            var progressBar = {
                intervalId: null,
                hideEta: null,
                maxHideTime: null
            };
            var response = {
                toastId: toastId,
                state: 'visible',
                startTime: new Date(),
                options: options,
                map: map
            };

            personalizeToast();

            displayToast();

            handleEvents();

            obj.publish(response);

            if (options.debug && console) {
                console.log(response);
            }

            return $toastElement;

            function escapeHtml(source) {
                if (source == null) {
                    source = '';
                }

                return source
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
            }

            function personalizeToast() {
                setIcon();
                setTitle();
                setMessage();
                setCloseButton();
                setProgressBar();
                setRTL();
                setSequence();
                setAria();
            }

            function setAria() {
                var ariaValue = '';
                switch (map.iconClass) {
                    case 'toast-success':
                    case 'toast-info':
                        ariaValue =  'polite';
                        break;
                    default:
                        ariaValue = 'assertive';
                }
                $toastElement.attr('aria-live', ariaValue);
            }

            function handleEvents() {
                if (options.closeOnHover) {
                    $toastElement.hover(stickAround, delayedHideToast);
                }

                if (!options.onclick && options.tapToDismiss) {
                    $toastElement.click(hideToast);
                }

                if (options.closeButton && $closeElement) {
                    $closeElement.click(function (event) {
                        if (event.stopPropagation) {
                            event.stopPropagation();
                        } else if (event.cancelBubble !== undefined && event.cancelBubble !== true) {
                            event.cancelBubble = true;
                        }

                        if (options.onCloseClick) {
                            options.onCloseClick(event);
                        }

                        hideToast(true);
                    });
                }

                if (options.onclick) {
                    $toastElement.click(function (event) {
                        options.onclick(event);
                        hideToast();
                    });
                }
            }

            function displayToast() {
                $toastElement.hide();

                $toastElement[options.showMethod](
                    {duration: options.showDuration, easing: options.showEasing, complete: options.onShown}
                );

                if (options.timeOut > 0) {
                    intervalId = setTimeout(hideToast, options.timeOut);
                    progressBar.maxHideTime = parseFloat(options.timeOut);
                    progressBar.hideEta = new Date().getTime() + progressBar.maxHideTime;
                    if (options.progressBar) {
                        progressBar.intervalId = setInterval(updateProgress, 10);
                    }
                }
            }

            function setIcon() {
                if (map.iconClass) {
                    $toastElement.addClass(options.toastClass).addClass(iconClass);
                }
            }

            function setSequence() {
                if (options.newestOnTop) {
                    $container.prepend($toastElement);
                } else {
                    $container.append($toastElement);
                }
            }

            function setTitle() {
                if (map.title) {
                    var suffix = map.title;
                    if (options.escapeHtml) {
                        suffix = escapeHtml(map.title);
                    }
                    $titleElement.append(suffix).addClass(options.titleClass);
                    $toastElement.append($titleElement);
                }
            }

            function setMessage() {
                if (map.message) {
                    var suffix = map.message;
                    if (options.escapeHtml) {
                        suffix = escapeHtml(map.message);
                    }
                    $messageElement.append(suffix).addClass(options.messageClass);
                    $toastElement.append($messageElement);
                }
            }

            function setCloseButton() {
                if (options.closeButton) {
                    $closeElement.addClass(options.closeClass).attr('role', 'button');
                    $toastElement.prepend($closeElement);
                }
            }

            function setProgressBar() {
                if (options.progressBar) {
                    $progressElement.addClass(options.progressClass);
                    $toastElement.prepend($progressElement);
                }
            }

            function setRTL() {
                if (options.rtl) {
                    $toastElement.addClass('rtl');
                }
            }

            function shouldExit(options, map) {
                if (options.preventDuplicates) {
                    if (map.message === previousToast) {
                        return true;
                    } else {
                        previousToast = map.message;
                    }
                }
                return false;
            }

            function hideToast(override) {
                var method = override && options.closeMethod !== false ? options.closeMethod : options.hideMethod;
                var duration = override && options.closeDuration !== false ?
                    options.closeDuration : options.hideDuration;
                var easing = override && options.closeEasing !== false ? options.closeEasing : options.hideEasing;
                if ($(':focus', $toastElement).length && !override) {
                    return;
                }
                clearTimeout(progressBar.intervalId);
                return $toastElement[method]({
                    duration: duration,
                    easing: easing,
                    complete: function () {
                        obj.removeToast($toastElement);
                        clearTimeout(intervalId);
                        if (options.onHidden && response.state !== 'hidden') {
                            options.onHidden();
                        }
                        response.state = 'hidden';
                        response.endTime = new Date();
                        obj.publish(response);
                    }
                });
            }

            function delayedHideToast() {
                if (options.timeOut > 0 || options.extendedTimeOut > 0) {
                    intervalId = setTimeout(hideToast, options.extendedTimeOut);
                    progressBar.maxHideTime = parseFloat(options.extendedTimeOut);
                    progressBar.hideEta = new Date().getTime() + progressBar.maxHideTime;
                }
            }

            function stickAround() {
                clearTimeout(intervalId);
                progressBar.hideEta = 0;
                $toastElement.stop(true, true)[options.showMethod](
                    {duration: options.showDuration, easing: options.showEasing}
                );
            }

            function updateProgress() {
                var percentage = ((progressBar.hideEta - (new Date().getTime())) / progressBar.maxHideTime) * 100;
                $progressElement.width(percentage + '%');
            }
        },

        getOptions:function () {
            return $.extend({},  obj.getDefaults(), obj.options);
        },

        removeToast:function ($toastElement) {
            if (!$container) { $container =  obj.getContainer(); }
            if ($toastElement.is(':visible')) {
                return;
            }
            $toastElement.remove();
            $toastElement = null;
            if ($container.children().length === 0) {
                $container.remove();
                previousToast = undefined;
            }
        }
    };
    exports('toastr', obj);
})