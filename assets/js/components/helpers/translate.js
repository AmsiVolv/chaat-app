import Polyglot from "node-polyglot";
import { phrases } from "./phrases.cz";

// https://www.npmjs.com/package/node-polyglot
const polyglot = new Polyglot({ phrases: phrases });

export default function translate(key) {
  return polyglot.t(key);
}
