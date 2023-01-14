import mysql from "serverless-mysql";

export const db = mysql({
  config: {
    host: "localhost",
    port: "3306",
    user: "root",
    password: "",
    database: "social_media",
    multipleStatements: true,
  },
});

export async function query(q, d) {
  try {
    const data = await db.query(q, d);
    db.end();
    return data;
  } catch (error) {
    console.log(error);
    throw error;
  }
}
