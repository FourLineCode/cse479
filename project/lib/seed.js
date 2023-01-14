const fs = require("fs/promises");
const { db, query } = require("./db.js");

(async () => {
  const seedQuery = await fs.readFile(process.cwd() + "/mysql/query.sql", "utf-8");
  await query(seedQuery);
  db.quit();
  console.log("Database seeded successfully");
})();
