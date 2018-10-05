class App
{
    static run()
    {
        this._inTitle = document.getElementById('inTitle');
        this._inCategoryId = document.getElementById('inCategoryId');
        this._divCatagories = document.getElementById('divCatagories');
        this._inMethod = document.getElementById('inMethod');

        this._inMethod.addEventListener('change', () =>
        {
            this._categoriesRefresh();
        });

        document.getElementById('addCategory').addEventListener('click', e =>
        {
            e.preventDefault();
            this._inTitle.style.borderColor = "black";
            const title = this._inTitle.value;

            if (this._inTitle.value.trim().length !== 0)
            {
                const id = this._inCategoryId.options[this._inCategoryId.selectedIndex].value;

                this._categoriesAdd(title, id, response =>
                {
                    if (response['status'])
                    {
                        this._categoriesRefresh();
                    }
                    else
                    {
                        alert(response['message']);
                    }
                });
            }
            else
            {
                this._inTitle.style.borderColor = "red";
            }
        });

        this._categoriesRefresh();
    }

    static _categoriesRefresh()
    {
        this._inTitle.value = '';

        this._divCatagories.innerHTML = '';
        this._inCategoryId.options.length = 0;
        const option = document.createElement('option');
        option.value = '0';
        this._inCategoryId.appendChild(option);

        const method = this._inMethod.options[this._inMethod.selectedIndex].value;

        this._categoriesRetrieve(method, response =>
        {
            if (response['status'])
            {
                this._categoriesDisplay(response['categories']);
            }
            else
            {
                alert(response['message']);
            }
        });
    }

    static _categoriesRetrieve(method, callback)
    {
        const url = 'api/categories?method=' + method;

        const xmlHttp = new XMLHttpRequest();

        xmlHttp.onreadystatechange = () =>
        {
            if (xmlHttp.readyState === 4 && xmlHttp.status === 200)
            {
                const response = JSON.parse(xmlHttp.responseText);
                callback(response);
            }
        };

        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    }

    static _categoriesAdd(title, id, callback)
    {
        const url = 'api/categories';

        const data = new FormData();
        data.append('title', title);
        data.append('parent_id', id);

        const xmlHttp = new XMLHttpRequest();

        xmlHttp.onreadystatechange = () =>
        {
            if (xmlHttp.readyState === 4 && xmlHttp.status === 200)
            {
                const response = JSON.parse(xmlHttp.responseText);
                callback(response);
            }
        };

        xmlHttp.open("POST", url, true);
        xmlHttp.send(data);
    }

    static _categoriesDisplay(categories, depth = -1)
    {
        depth++;

        for (let i = 0; i < categories.length; i++)
        {
            const title = '&nbsp;'.repeat(depth * 3) + '-' + categories[i]['title'];

            this._divCatagories.innerHTML += title + '<br>';

            const option = document.createElement('option');
            option.innerHTML = title;
            option.value = categories[i]['id'];
            this._inCategoryId.appendChild(option);

            if (categories[i]['childs'])
            {
                this._categoriesDisplay(categories[i]['childs'], depth);
            }
        }
    }
}