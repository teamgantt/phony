var run = function () {
    var fetchVersions = function () {
        var request = new XMLHttpRequest();

        request.onerror = function (e) {
            console.error('Unable to load version data: ' + e.message);
        };

        request.onload = function () {
            if (request.status < 200 || request.status > 499) {
                console.error(
                    'Unable to load version data: ' +
                    request.statusText + ' (' + request.status + ')'
                );

                return;
            }

            var versions = JSON.parse(request.responseText);
            var currentVersion = document.body.getAttribute('data-version');
            var versionSelect = document.createElement('select');

            if (versions.indexOf(currentVersion) < 0) {
                versions.push(currentVersion);
            }

            for (var i = 0; i < versions.length; ++i) {
                var version = document.createElement('option');
                version.textContent = versions[i];
                version.setAttribute('value', versions[i]);

                if (versions[i] === currentVersion) {
                    version.setAttribute('selected', 'selected');
                }

                versionSelect.appendChild(version);
            }

            versionSelect.addEventListener(
                'change',
                function () {
                    selectedVersion =
                        versionSelect.options[versionSelect.selectedIndex]
                            .value;

                    window.location.pathname = window.location.pathname.replace(
                        /\/[^/]+\/?$/,
                        '/' + selectedVersion + '/'
                    );
                }
            );

            document.getElementById('version').appendChild(versionSelect);
        };

        request.open('GET', '../data/versions.json', true);
        request.send();
    };

    fetchVersions();

    var contentElement = document.getElementById('content');
    var tocElement = document.getElementById('toc');
    var tocListElement = contentElement.querySelector('ul');
    var tocListElementCopy = tocListElement.cloneNode(true);

    tocListElement.style.display = 'none';
    tocElement.appendChild(tocListElementCopy);

    var phonyLink = document.createElement('a');
    phonyLink.href = '#phony';
    phonyLink.appendChild(document.createTextNode('Phony'));

    var phonyListItem = document.createElement('li');
    phonyListItem.style.display = 'none';
    phonyListItem.appendChild(phonyLink);

    tocListElementCopy.insertBefore(
        phonyListItem,
        tocListElementCopy.querySelector('li')
    );

    var activateTocHeading = function (data) {
        var activeElements = tocElement.querySelectorAll('.active');

        for (var i = 0; i < activeElements.length; ++i) {
            activeElements[i].classList.remove('active');
        }

        if (!data) {
            return;
        }

        var node = data.parent;
        node.classList.add('active');

        while (
            node.parentNode &&
            node.parentNode.parentNode &&
            'LI' == node.parentNode.parentNode.tagName
        ) {
            node = node.parentNode.parentNode;

            node.classList.add('active');
        }
    };

    var redrawToc = function () {
        tocElement.style.marginLeft = (870 - document.body.scrollLeft) + 'px';
        gumshoe.setDistances();
    };

    var tocShowElement = document.getElementById('toc-show');
    var tocHideElement = document.getElementById('toc-hide');

    var showToc = function () {
        tocShowElement.style.display = 'none';
        tocHideElement.style.display = 'inline';
        tocListElement.style.display = 'block';

        gumshoe.setDistances();
    };

    var hideToc = function () {
        tocHideElement.style.display = 'none';
        tocShowElement.style.display = 'inline';
        tocListElement.style.display = 'none';

        gumshoe.setDistances();
    };

    var dispatch = function (event) {
        if (window.location.hash) {
            hash = decodeURIComponent(window.location.hash.substring(1));

            var target;

            try {
                target = document.querySelector('#' + hash);
            } catch (e) {
                // not a standard anchor link
            }

            if (target && target.classList.contains('anchor')) {
                document.title = target.parentNode.innerText + ' - Phony';
            }

            target = null;

            try {
                target = document.querySelector('a[name="' + hash + '"]');
            } catch (e) {
                // not a standard anchor link
            }

            if (target) {
                var matches = hash.match(/^(\w+)\.(\w+)$/);

                if (matches) {
                    if ('facade' === matches[1]) {
                        document.title = matches[2] + '() - Phony';
                    } else {
                        document.title =
                            '$' + matches[1] + '->' + matches[2] + '() - Phony';
                    }
                }

                var matches = hash.match(/^(\w+)\.(\w+)\.(\w+)$/);

                if (matches) {
                    document.title =
                        '$' + matches[1] +
                        ' ' + matches[2] +
                        ' ' + matches[3] +
                        ' - Phony';
                }
            }
        } else {
            document.title = 'Phony';
        }

        if ('#toc' === window.location.hash) {
            if (event) {
                event.preventDefault();
            }

            showToc();
            tocListElement.scrollIntoView();
        }
    };

    window.addEventListener('hashchange', dispatch);
    document.addEventListener('scroll', _.throttle(redrawToc, 10));
    tocHideElement.addEventListener('click', hideToc);

    gumshoe.init(
        {
            selector: '#toc > ul a',
            offset: 30,
            callback: activateTocHeading
        }
    );
    dispatch();
    redrawToc();
};

document.addEventListener('DOMContentLoaded', run);
