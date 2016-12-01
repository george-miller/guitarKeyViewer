module Main where

import Control.Applicative ((<$>), optional)
import Data.Maybe (fromMaybe)
import Data.Text (Text)
import Data.Text.Lazy (unpack)
import Happstack.Server
import Text.Blaze.Html5 (Html, (!), a, form, input, p, toHtml, label, html)
import Text.Blaze.Html5.Attributes (action, enctype, href, name, size, type_, value)
import qualified Text.Blaze.Html5 as H
import qualified Text.Blaze.Html5.Attributes as A
import Control.Monad (msum)
import Text.Blaze.Internal

main :: IO ()
main = simpleHTTP nullConf myApp
myApp :: ServerPart Response
myApp = msum
  [ dir "echo" $ queryParams]


queryParams :: ServerPart Response
queryParams =
    do mFoo <- optional $ lookText "foo"
       ok $ template "query params" $ do
         p $ toHtml "foo is set to: " >> toHtml (show mFoo)
         p $ toHtml "change the url to set it to something else."
  
template :: String -> Html -> Response
template title body = toResponse $
  H.html $ do
    H.head $ do
      H.title (toHtml title)
    H.body $ do
      body
