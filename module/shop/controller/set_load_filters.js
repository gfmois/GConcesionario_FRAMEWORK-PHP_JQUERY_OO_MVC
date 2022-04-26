import { loadCars } from './ShopController.js'
import { setLocalStorage } from './setLocalStorage.js'
import { pagination } from './pagination.js'

function setFilters() {
    let lData = JSON.parse(localStorage.getItem('filters'))
    let checkboxActive = []

    let urlParams = window.location.search
    let params = new URLSearchParams(urlParams)

    if (!params.get('pagination')) {
        params.set('pagination', 1)
        window.history.replaceState({}, '', `${window.location.pathname}?${decodeURIComponent(params)}`)
    }

    let page_n = params.get('page_n') != null ? params.get('page_n') : 1;
    let url = friendlyURL('?page=shop&op=fromFilters');

    ajaxPromise('POST', url, 'json', {filters: lData, pagination: 1})
        .then((data) => {
            console.log(data);

            if (data != null) {
                loadCars(data, 0)
            } else {
                $.each($('input[type=checkbox]'), function(index, item) {
                    if ($(this).prop("checked") == true) {
                        checkboxActive.push($(this))
                    }
                });

                if (checkboxActive.length > 0) {
                    loadCars([], 1)
                    console.log('B');
                } else {
                    loadCars([], 0)
                    console.log('C');
                }

            }

            const querystring = window.location.search
            const params = new URLSearchParams(querystring)

            let lStorageJSON = JSON.parse(localStorage.getItem('filters'))

            $.each(lStorageJSON, function(filterName, filterItems) {
                params.delete(filterName)
                for (let i = 0; i < filterItems.length; i++) {
                    if (lStorageJSON[filterName].length > 0) {
                        params.delete(filterName)
                        params.append(filterName, filterItems.toString())
                    }
                }
            });

            console.log('Set Load Filters');
            window.history.replaceState({}, '', `${window.location.pathname}?${decodeURIComponent(params)}`)
            pagination(page_n, 1)
        });
}

function loadFiltersDiv() {
    let onKey = null;
    pausedPromise(350).then(() => {
        let filterIcon = document.createElement('i')

        filterIcon.className = "fas fa-filter"

        let filterDiv = document.createElement('div')
        let filterHeader = document.createElement('div')
        let titleDiv = document.createElement('div')
        let titleText = document.createElement('span')
        let spanBtn = document.createElement('span')

        spanBtn.className = "btn_filter"

        filterDiv.className = "filter"
        filterHeader.className = "filter-header"

        titleText.appendChild(document.createTextNode('Filtros'))

        // Titulo filtros
        titleDiv.appendChild(titleText)
        titleDiv.appendChild(spanBtn)

        let selectedFilters = document.createElement('div')
        let numberOfFilters = document.createElement('span')

        selectedFilters.className = "f-9"
        numberOfFilters.id = "filter_selected"

        numberOfFilters.appendChild(document.createTextNode('0'))
        selectedFilters.appendChild(document.createTextNode('Filtros seleccionados: '))
        selectedFilters.appendChild(numberOfFilters)

        filterHeader.appendChild(titleDiv)
        filterHeader.appendChild(selectedFilters)

        let filterContent = document.createElement('div')
        let filterUl = document.createElement('ul')
        let cityInput = document.createElement('input')
        let cityUl = document.createElement('ul')
        let cityLi = document.createElement('li')

        filterContent.className = "filter-content"

        cityInput.className = "cityInput"
        cityInput.type = "text"
        cityInput.placeholder = "Ciudad"

        cityUl.appendChild(cityLi)
        cityLi.appendChild(cityInput)

        filterContent.appendChild(cityUl)

        let fOptions = [
            ["count", "type", "model"], // Order By
            [], // Brands
            [], // Models,
            [], // Type
            [], // Type of Body
            [], // Category
            ['Black', "Red", "Blue", "White"], // Colors
            ['2', '3', '4', '5', '6'] // Doors
        ]
        let fTitles = ["Order By", "Brands", "Models", "Type", "Body Type", "Category", "Colors", "N_Doors"]

        let idOption = [
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            []
        ]

        // idOption.length = 7

        let obKey = []

        ajaxPromise('GET', friendlyURL("?page=shop&op=cars"), 'json').then((json) => {
            let jsonWithCarsInfo = {
                brand: json[0],
                model: json[1],
                type: json[2],
                body: json[3],
                category: json[4]
            }
            
            obKey = Object.keys(json)
            $.each(jsonWithCarsInfo, function(jsonKey, items) {
                $.each(items, function(indexInArray, itemName) {
                    switch (jsonKey) {
                        case 'brand':
                            console.log(itemName[1]);
                            fOptions[1].push(itemName[1]); // Name
                            idOption[1].push(itemName[0]); // ID
                            break;
                        case 'model':
                            fOptions[2].push(itemName[1]) // Name
                            idOption[2].push(itemName[0]) // ID
                            break;
                        case 'type':
                            fOptions[3].push(itemName[1])
                            idOption[3].push(itemName[0])
                            break;
                        case 'body':
                            fOptions[4].push(itemName[1])
                            idOption[4].push(itemName[0])
                            break;
                        case 'category':
                            fOptions[5].push(itemName[1])
                            idOption[5].push(itemName[0])
                            break;
                    }
                });
            });
            // console.log(fOptions);
        })

        pausedPromise(350).then(() => {
            for (let i = 0; i < fOptions.length; i++) {
                // if (i == 5) break;
                let filterLi = document.createElement('li')
                let liSpan = document.createElement('span')
                let filterSpanIcon = document.createElement('i')
                let fContentDiv = document.createElement('div')

                liSpan.className = "title"
                filterSpanIcon.className = "fas fa-angle-up"

                liSpan.appendChild(document.createTextNode(fTitles[i]))
                liSpan.appendChild(filterSpanIcon)

                for (let j = 0; j < fOptions[i].length; j++) {
                    let fContentItem = document.createElement('div')
                    let fInputCB = document.createElement('input')
                    let fInputLabel = document.createElement('p')

                    fContentDiv.className = "content"
                    fContentDiv.id = fTitles[i]
                    fContentItem.className = "content-item"
                    fInputCB.type = "checkbox"
                    fInputCB.name = ""
                    fInputCB.id = idOption[i][j];
                    fInputLabel.className = "f-9"

                    fInputLabel.appendChild(document.createTextNode(fOptions[i][j]))
                    fContentItem.appendChild(fInputCB)
                    fContentItem.appendChild(fInputLabel)

                    fContentDiv.appendChild(fContentItem)
                }

                filterLi.appendChild(liSpan)
                filterLi.appendChild(fContentDiv)
                filterUl.appendChild(filterLi)
            }

            filterContent.appendChild(filterUl)

            filterDiv.appendChild(filterHeader)
            filterDiv.appendChild(filterContent)

            document.getElementById('fDiv').appendChild(filterDiv)

            let checked;

            $(".content").toggleClass("close");

            $('.content-item').on('change', () => {
                setLocalStorage()
                setFilters();
            })

            $('.cityInput').on('keyup', () => {
                setLocalStorage()
                setFilters()
            })

            $(".filter-content .title").click(function() {
                $(this).next().toggleClass("close");
                $(this).children().toggleClass("open");
            });

            $('.filter-header > div > .btn_filter').on('click', function() {
                $('.filterDiv>.btn_filter').show()
            })

            $("input[type=checkbox]").click(function() {
                checked = 0;
                $("input[type=checkbox]:checked").each(() => {
                    if ($(this).is(":checked")) {
                        checked++
                    }
                })
                $("#filter_selected").html(checked);
            });

            $(".bg-overlay").click(function() {
                $("#filter").hide();
            });
        })
    })
}

export { setFilters, loadFiltersDiv }