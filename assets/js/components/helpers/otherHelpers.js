export const getCitationUrl = (str) => {
  if (str !== undefined) {
    const bookID = str.substr(str.indexOf("?") + 1);

    return `https://katalog.vse.cz/Record/${bookID}/Cite?layout=lightbox`;
  }
};
