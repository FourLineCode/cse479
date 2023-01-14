import fs from "fs/promises";
import { db, query } from "./db.js";

(async () => {
  const seedQuery = await fs.readFile(process.cwd() + "/mysql/query.sql", "utf-8");
  await query(seedQuery);
  db.quit();
  console.log("Database seeded successfully");
})();
