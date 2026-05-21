(function () {
  const sessionId = localStorage.getItem("session_id") || crypto.randomUUID();
  localStorage.setItem("session_id", sessionId);

  function sendEvent(type, data = {}) {
    fetch("/api/track.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        session_id: sessionId,
        event: type,
        url: window.location.href,
        ref: document.referrer,
        time: Date.now(),
        data: data
      })
    });
  }

  window.analytics = {
    pageView: () => sendEvent("page_view"),
    lead: () => sendEvent("lead"),
    contactClick: () => sendEvent("contact_click"),
    phoneClick: () => sendEvent("phone_click"),
    custom: (name, data) => sendEvent(name, data)
  };

  analytics.pageView();
})();