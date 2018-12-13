const friends = firebase.database().ref().child('friends'),
    noAreaCode = /^[0-9]{7}$/

processUpload = () => {
    var uploadInfo = document.getElementById('upload')
    if ('files' in uploadInfo) {
        var reader = new FileReader()
        reader.onload = function(event) {
            var jsonObj = JSON.parse(event.target.result)

            for (i in jsonObj) {
                friends.push().set({
                    name: jsonObj[i].name,
                    phone: jsonObj[i].phone,
                    age: jsonObj[i].age
                })
            }
        }
        reader.readAsText(uploadInfo.files[0])
    }
}

friends.on('child_added', (myFunction) => {
    const table = document.getElementById('table')
    buildTable(myFunction, table)
})

friends.on('child_removed', (myFunction) => {
    const tBody = document.getElementById('table').getElementsByTagName('tbody')[0],
        row = document.querySelector(`tr[id='${myFunction.key}']`)
    tBody.removeChild(row)
})

buildTable = (myFunction, table) => {
    const row = table.insertRow(1),
        name = row.insertCell(0),
        phone = row.insertCell(1),
        age = row.insertCell(2),
        action = row.insertCell(3)

    row.setAttribute('id', myFunction.key)

    name.innerHTML = myFunction.val().name
    phoneValue = phoneFormat(myFunction.val().phone)
    phone.innerHTML = phoneValue
    age.innerHTML = myFunction.val().age
    action.innerHTML = `<button id='${myFunction.key}' onclick='deleteRow(event)'>Delete</button>`
}
buildSearchTable = (myFunction, table) => {
    const row = table.insertRow(1),
        name = row.insertCell(0),
        phone = row.insertCell(1),
        age = row.insertCell(2)

    row.setAttribute('id', myFunction.key)

    name.innerHTML = myFunction.val().name
    phoneValue = phoneFormat(myFunction.val().phone)
    phone.innerHTML = phoneValue
    age.innerHTML = myFunction.val().age
}

noResults = (myFunction, table) => {
    const row = table.insertRow(1)
    row.innerHTML = `<td id='${myFunction.key}'>No results</td>`
}

phoneFormat = phone => noAreaCode.test(phone) ? phone.toString().replace(/(\d{3})(\d{4})/, '$1-$2') : phone.toString().replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3')

deleteRow = (event) => {
    const target = event.target || event.srcElement,
        row = target.id

    friends.child(row).remove()
}

deleteAll = () => friends.remove()

newEntry = () => {
    const formDiv = document.getElementById('newEntry'),
        inputs = formDiv.children,
        newNameEntry = inputs[1].value,
        newPhoneEntry = inputs[4].value,
        newAgeEntry = inputs[7].value,
        namePattern = /^[A-Z]{1}[a-z]+( [A-Z]{1}[a-z]*)? [A-Z]{1}[a-z]+$/,
        phonePattern = /(^\d{7}$|^\d{10}$)/,
        agePattern = /(^\d{1}$|^\d{2}$)/

    if (namePattern.test(newNameEntry) && phonePattern.test(newPhoneEntry) && agePattern.test(newAgeEntry)) {
        friends.push().set({ 
        name: newNameEntry, 
        phone: newPhoneEntry, 
        age: newAgeEntry 
        })
    } else if (!namePattern.test(newNameEntry)){
        alert('The name ' + inputs[1].value + ' is an invalid input')
    } else if (!phonePattern.test(newPhoneEntry)){
        alert('The phone number ' + inputs[4].value + ' is an invalid input\nPlease do not use hyphens or spaces')
    } else if(!agePattern.test(newAgeEntry)) {
        alert('The age ' + inputs[7].value + ' is an invalid input\nPlease choose a number between 00-99\n')
    }
}

search = () => {
    const searchQuery = document.getElementById('searchQuery'),
        searchTable = document.getElementById('searchTable'),
        rows = searchTable.querySelectorAll('[id]'),
        query = searchQuery.value.toLowerCase(),
        regexQuery = new RegExp(query)

    rows.forEach( (row) => row.remove() )

    friends.on('child_added', (myFunction) => {
        if(regexQuery.test(myFunction.val().name.toLowerCase())) buildSearchTable(myFunction, searchTable)
    })
}