module.exports = {
  extractSesiId: function(requestParams, response, context, ee, next) {
    const location = response.headers['location'] || response.headers['Location'];
    if (location) {
      const match = location.match(/ujian\/([0-9]+)\/soal/);
      if (match && match[1]) {
        context.vars.sesi_id = match[1];
      } else {
        console.error("NO MATCH FOUND IN LOCATION:", location);
      }
    } else {
      console.error("NO LOCATION HEADER FOUND!");
    }
    return next();
  }
};
