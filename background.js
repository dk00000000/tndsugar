function getword(info,tab) {
  console.log("Word " + info.selectionText + " was clicked.");
  chrome.tabs.create({  
    url: "http://www.google.com/search?q=" + info.selectionText,
  });           
}
chrome.contextMenus.create({
  title: "Search: %s", 
  contexts:["selection"], 
  onclick: getword,
});

/*browser.contextMenus.create({
  id: "clickme",
  title: "Click me!",
  contexts: ["all"]
});

browser.contextMenus.create({
  id: "clickme-separator-1",
  type: "separator",
  contexts: ["all"]
});

browser.contextMenus.create({
  id: "clickme-radio-1",
  type: "radio",
  title: "Choose me - radio 1!",
  contexts: ["all"],
  checked: true
});

browser.contextMenus.create({
  id: "clickme-radio-2",
  type: "radio",
  title: "Choose me - radio 2!",
  contexts: ["all"],
  checked: false
});

browser.contextMenus.create({
  id: "clickme-separator-2",
  type: "separator",
  contexts: ["all"]
});

browser.contextMenus.create({
  id: "clickme-checkbox",
  type: "checkbox",
  title: "Uncheck me: checkbox!",
  contexts: ["all"],
  checked: true
});*/